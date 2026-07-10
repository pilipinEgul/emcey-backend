<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    private const OPEN_HOUR = 10;
    private const CLOSE_HOUR = 19;
    private const CLOSED_WEEKDAYS = [1]; // 1 = Monday, ISO-8601

    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();

        $appointment = DB::transaction(function () use ($data) {
            $service = Service::findOrFail($data['service_id']);
            $duration = $service->duration_minutes ?? 60;
            $scheduledAt = Carbon::parse($data['scheduled_at']);

            abort_if(
                in_array($scheduledAt->dayOfWeekIso, self::CLOSED_WEEKDAYS, true),
                409,
                'The studio is closed on this day. Please pick another date.',
            );

            $endsAt = $scheduledAt->copy()->addMinutes($duration);

            $conflict = Appointment::query()
                ->where('service_id', $service->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('scheduled_at', '<', $endsAt)
                ->whereRaw(
                    'DATE_ADD(scheduled_at, INTERVAL COALESCE(duration_minutes, ?) MINUTE) > ?',
                    [$duration, $scheduledAt]
                )
                ->exists();

            abort_if($conflict, 409, 'This time slot is no longer available.');

            return Appointment::create([
                'service_id' => $service->id,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'scheduled_at' => $scheduledAt,
                'duration_minutes' => $duration,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'promo_code' => $data['promo_code'] ?? null,
                'total_amount' => $service->promo_price ?? $service->price,
                'payment_status' => 'unpaid',
            ]);
        });

        return (new AppointmentResource($appointment->load('service')))
            ->response()
            ->setStatusCode(201);
    }

    public function availability(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'date' => ['required', 'date'],
        ]);

        $service = Service::findOrFail($request->integer('service_id'));
        $date = CarbonImmutable::parse($request->string('date'));

        $day = $this->buildDay($service, $date);

        return response()->json(['data' => $day]);
    }

    public function availabilityRange(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'from' => ['nullable', 'date'],
            'days' => ['nullable', 'integer', 'min:1', 'max:90'],
        ]);

        $service = Service::findOrFail($request->integer('service_id'));
        $from = $request->filled('from')
            ? CarbonImmutable::parse($request->string('from'))->startOfDay()
            : CarbonImmutable::today();
        $days = $request->integer('days', 35);

        $start = $from;
        $end = $from->addDays($days - 1)->endOfDay();

        $taken = Appointment::query()
            ->where('service_id', $service->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('scheduled_at', [$start, $end])
            ->get(['scheduled_at'])
            ->groupBy(fn ($a) => $a->scheduled_at->toDateString())
            ->map(fn ($rows) => $rows->map(fn ($r) => $r->scheduled_at->format('H:i'))->all());

        $payload = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $from->addDays($i);
            $payload[] = $this->buildDay($service, $date, $taken->get($date->toDateString(), []));
        }

        return response()->json([
            'data' => [
                'service_id' => $service->id,
                'duration_minutes' => $service->duration_minutes,
                'open_hour' => self::OPEN_HOUR,
                'close_hour' => self::CLOSE_HOUR,
                'closed_weekdays' => self::CLOSED_WEEKDAYS,
                'days' => $payload,
            ],
        ]);
    }

    private function buildDay(Service $service, CarbonImmutable $date, ?array $takenTimes = null): array
    {
        $isClosed = in_array($date->dayOfWeekIso, self::CLOSED_WEEKDAYS, true);
        $isPast = $date->isBefore(CarbonImmutable::today());

        if ($takenTimes === null) {
            $takenTimes = Appointment::query()
                ->where('service_id', $service->id)
                ->whereDate('scheduled_at', $date->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])
                ->pluck('scheduled_at')
                ->map(fn ($d) => $d->format('H:i'))
                ->all();
        }

        $step = max(30, (int) ($service->duration_minutes ?? 60));
        $slots = [];

        if (! $isClosed && ! $isPast) {
            for ($minutes = self::OPEN_HOUR * 60; $minutes + $step <= self::CLOSE_HOUR * 60; $minutes += $step) {
                $time = sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
                $slots[] = [
                    'time' => $time,
                    'available' => ! in_array($time, $takenTimes, true),
                ];
            }
        }

        $availableCount = count(array_filter($slots, fn ($s) => $s['available']));

        return [
            'date' => $date->toDateString(),
            'weekday' => $date->englishDayOfWeek,
            'is_closed' => $isClosed,
            'is_past' => $isPast,
            'available_count' => $availableCount,
            'total_slots' => count($slots),
            'slots' => $slots,
        ];
    }
}
