<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Mail\BookingStatusUpdate;
use App\Models\Appointment;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query()->with('service')->latest('scheduled_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }
        if ($request->filled('from')) {
            $query->whereDate('scheduled_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('scheduled_at', '<=', $request->date('to'));
        }
        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $perPage = min(100, max(1, $request->integer('per_page', 25)));

        return AppointmentResource::collection($query->paginate($perPage));
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load('service'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['sometimes', 'in:pending,confirmed,cancelled,completed'],
            'payment_status' => ['sometimes', 'in:unpaid,partial,paid'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        $originalStatus = $appointment->status;

        $appointment->update($data);

        $statusChanged = array_key_exists('status', $data)
            && $appointment->status !== $originalStatus;

        // When a booking is confirmed, push it to the studio's Google Calendar
        // (once). Best-effort: a calendar failure must not fail the status update.
        if (($data['status'] ?? null) === 'confirmed' && blank($appointment->google_event_id)) {
            $event = app(GoogleCalendarService::class)
                ->createEventForAppointment($appointment->load('service'));

            if ($event !== null) {
                $appointment->forceFill([
                    'google_event_id' => $event['id'],
                    'google_event_link' => $event['link'],
                ])->save();
            }
        }

        // Notify the customer when their status changes. Best-effort.
        if ($statusChanged && in_array($appointment->status, ['confirmed', 'cancelled', 'completed'], true)) {
            try {
                Mail::to($appointment->customer_email)
                    ->send(new BookingStatusUpdate($appointment->load('service')));
            } catch (\Throwable $e) {
                Log::warning('Status-update email failed: ' . $e->getMessage());
            }
        }

        return new AppointmentResource($appointment->load('service'));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted.']);
    }
}
