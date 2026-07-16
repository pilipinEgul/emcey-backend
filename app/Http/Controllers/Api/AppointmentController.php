<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Mail\BookingConfirmation;
use App\Mail\BookingStatusUpdate;
use App\Mail\NewBookingAdminAlert;
use App\Models\Closure;
use App\Models\Service;
use App\Models\Setting;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    private ?array $schedule = null;

    /**
     * Admin-editable booking schedule from the `settings` table, falling back to
     * config/emcey.php defaults. slot_interval_minutes of 0 means "use the
     * service's own duration to space slots".
     *
     * @return array{open:int, close:int, interval:int}
     */
    private function schedule(): array
    {
        return $this->schedule ??= [
            'open' => (int) Setting::get('open_hour', config('emcey.booking.open_hour', 10)),
            'close' => (int) Setting::get('close_hour', config('emcey.booking.close_hour', 19)),
            'interval' => (int) Setting::get(
                'slot_interval_minutes',
                config('emcey.booking.slot_interval_minutes', 0),
            ),
        ];
    }

    /** ISO-8601 weekdays (1=Mon…7=Sun) the studio is closed every week. */
    private function closedWeekdays(): array
    {
        return Closure::query()->whereNotNull('weekday')->pluck('weekday')->all();
    }

    /**
     * Specific closed dates ("Y-m-d") within [$from, $to].
     *
     * @return array<string, string> date string => reason
     */
    private function closedDates(CarbonImmutable $from, CarbonImmutable $to): array
    {
        return Closure::query()
            ->whereNotNull('date')
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get()
            ->mapWithKeys(fn ($c) => [$c->date->toDateString() => (string) $c->reason])
            ->all();
    }

    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();

        $appointment = DB::transaction(function () use ($data) {
            $service = Service::findOrFail($data['service_id']);
            $duration = $service->duration_minutes ?? 60;
            $scheduledAt = Carbon::parse($data['scheduled_at']);

            $isClosed = in_array($scheduledAt->dayOfWeekIso, $this->closedWeekdays(), true)
                || Closure::query()->whereDate('date', $scheduledAt->toDateString())->exists();

            abort_if(
                $isClosed,
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

        $appointment->load('service');

        // Calendar sync happens when an admin *confirms* the booking
        // (see Admin\AppointmentController@update), not on this initial request —
        // pending bookings stay off the studio calendar until approved.

        // Best-effort emails — a mail failure must never fail the booking.
        try {
            Mail::to($appointment->customer_email)->send(new BookingConfirmation($appointment));

            $notify = config('emcey.notify_email');
            if (filled($notify)) {
                Mail::to($notify)->send(new NewBookingAdminAlert($appointment));
            }
        } catch (\Throwable $e) {
            Log::warning('Booking email failed: ' . $e->getMessage());
        }

        return (new AppointmentResource($appointment))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Public booking lookup by reference — lets a customer track their status
     * without an account. Returns only non-sensitive fields.
     */
    public function track(Request $request)
    {
        $data = $request->validate([
            'reference' => ['required', 'string', 'max:32'],
        ]);

        $appointment = Appointment::with('service')
            ->where('reference', trim($data['reference']))
            ->first();

        abort_if(
            ! $appointment,
            404,
            'No booking found with that reference. Please double-check and try again.',
        );

        return response()->json(['data' => $this->publicView($appointment)]);
    }

    /**
     * Public cancellation by reference. Requires a reason, blocks re-cancelling
     * or cancelling a completed booking, and removes the studio calendar event.
     */
    public function cancel(Request $request)
    {
        $data = $request->validate([
            'reference' => ['required', 'string', 'max:32'],
            'reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        $appointment = Appointment::with('service')
            ->where('reference', trim($data['reference']))
            ->first();

        abort_if($appointment === null, 404, 'No booking found with that reference.');

        abort_if(
            in_array($appointment->status, ['cancelled', 'completed'], true),
            409,
            "This booking is already {$appointment->status} and can no longer be cancelled.",
        );

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $data['reason'],
            'cancelled_at' => now(),
        ]);

        // Free the studio calendar if this booking had been confirmed/synced.
        if (filled($appointment->google_event_id)) {
            app(GoogleCalendarService::class)->deleteEventForAppointment($appointment);
        }

        try {
            Mail::to($appointment->customer_email)->send(new BookingStatusUpdate($appointment));
        } catch (\Throwable $e) {
            Log::warning('Cancellation email failed: ' . $e->getMessage());
        }

        return response()->json(['data' => $this->publicView($appointment->fresh('service'))]);
    }

    /**
     * Customer-facing subset of an appointment (no email/phone/amounts).
     *
     * @return array<string, mixed>
     */
    private function publicView(Appointment $appointment): array
    {
        return [
            'reference' => $appointment->reference,
            'status' => $appointment->status,
            'customer_name' => $appointment->customer_name,
            'scheduled_at' => $appointment->scheduled_at?->toIso8601String(),
            'duration_minutes' => $appointment->duration_minutes,
            'cancellation_reason' => $appointment->cancellation_reason,
            'service' => $appointment->service
                ? ['name' => $appointment->service->name]
                : null,
        ];
    }

    public function availability(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'date' => ['required', 'date'],
        ]);

        $service = Service::findOrFail($request->integer('service_id'));
        $date = CarbonImmutable::parse($request->string('date'));

        $day = $this->buildDay(
            $service,
            $date,
            $this->closedWeekdays(),
            $this->closedDates($date->startOfDay(), $date->startOfDay()),
        );

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

        $closedWeekdays = $this->closedWeekdays();
        $closedDates = $this->closedDates($start, CarbonImmutable::parse($end));

        // Google Calendar busy intervals per date ("Y-m-d" => [[startMin, endMin], …]).
        $busy = app(GoogleCalendarService::class)->busyIntervals($start, CarbonImmutable::parse($end));

        $payload = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $from->addDays($i);
            $dateStr = $date->toDateString();
            $payload[] = $this->buildDay(
                $service,
                $date,
                $closedWeekdays,
                $closedDates,
                $taken->get($dateStr, []),
                $busy[$dateStr] ?? [],
            );
        }

        return response()->json([
            'data' => [
                'service_id' => $service->id,
                'duration_minutes' => $service->duration_minutes,
                'open_hour' => $this->schedule()['open'],
                'close_hour' => $this->schedule()['close'],
                'slot_interval_minutes' => $this->schedule()['interval'],
                'closed_weekdays' => $closedWeekdays,
                'days' => $payload,
            ],
        ]);
    }

    /**
     * @param  array<int>  $closedWeekdays  ISO weekdays closed every week
     * @param  array<string, string>  $closedDates  "Y-m-d" => reason
     * @param  array<int, array{0:int,1:int}>  $busyIntervals  [startMinuteOfDay, endMinuteOfDay]
     */
    private function buildDay(
        Service $service,
        CarbonImmutable $date,
        array $closedWeekdays,
        array $closedDates,
        ?array $takenTimes = null,
        array $busyIntervals = [],
    ): array {
        $dateStr = $date->toDateString();
        $closureReason = $closedDates[$dateStr] ?? null;
        $isClosed = in_array($date->dayOfWeekIso, $closedWeekdays, true)
            || array_key_exists($dateStr, $closedDates);
        $isPast = $date->isBefore(CarbonImmutable::today());

        if ($takenTimes === null) {
            $takenTimes = Appointment::query()
                ->where('service_id', $service->id)
                ->whereDate('scheduled_at', $dateStr)
                ->whereIn('status', ['pending', 'confirmed'])
                ->pluck('scheduled_at')
                ->map(fn ($d) => $d->format('H:i'))
                ->all();
        }

        $duration = max(30, (int) ($service->duration_minutes ?? 60));
        $sched = $this->schedule();
        // Slots are spaced by the admin interval, or by the service duration when
        // no interval is set. Each slot still occupies the full service duration.
        $step = $sched['interval'] > 0 ? $sched['interval'] : $duration;
        $openMin = $sched['open'] * 60;
        $closeMin = $sched['close'] * 60;

        // Booked start times → minutes-of-day, for duration-aware overlap checks.
        $takenMinutes = array_map(
            fn ($t) => ((int) substr($t, 0, 2)) * 60 + (int) substr($t, 3, 2),
            $takenTimes,
        );

        $slots = [];

        if (! $isClosed && ! $isPast) {
            for ($minutes = $openMin; $minutes + $duration <= $closeMin; $minutes += $step) {
                $time = sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
                $slotEnd = $minutes + $duration;

                $overlaps = false;
                foreach ($takenMinutes as $taken) {
                    if ($taken < $slotEnd && $taken + $duration > $minutes) {
                        $overlaps = true;
                        break;
                    }
                }
                if (! $overlaps) {
                    foreach ($busyIntervals as [$busyStart, $busyEnd]) {
                        if ($busyStart < $slotEnd && $busyEnd > $minutes) {
                            $overlaps = true;
                            break;
                        }
                    }
                }

                $slots[] = [
                    'time' => $time,
                    'available' => ! $overlaps,
                ];
            }
        }

        $availableCount = count(array_filter($slots, fn ($s) => $s['available']));

        return [
            'date' => $dateStr,
            'weekday' => $date->englishDayOfWeek,
            'is_closed' => $isClosed,
            'closure_reason' => $closureReason,
            'is_past' => $isPast,
            'available_count' => $availableCount,
            'total_slots' => count($slots),
            'slots' => $slots,
        ];
    }
}
