<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $appointments = Appointment::with('service')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('scheduled_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'status' => $status,
            'statuses' => ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'],
        ]);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('service');

        return view('admin.appointments.show', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,no_show'],
            'payment_status' => ['required', 'in:unpaid,partial,paid,refunded'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $appointment->update($data);

        return back()->with('status', 'Appointment updated.');
    }
}
