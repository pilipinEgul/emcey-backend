<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Testimonial;
use Carbon\CarbonImmutable;

class DashboardController extends Controller
{
    public function index()
    {
        $today = CarbonImmutable::today();

        return response()->json([
            'data' => [
                'appointments' => [
                    'pending' => Appointment::where('status', 'pending')->count(),
                    'confirmed' => Appointment::where('status', 'confirmed')->count(),
                    'upcoming' => Appointment::whereIn('status', ['pending', 'confirmed'])
                        ->where('scheduled_at', '>=', $today)
                        ->count(),
                    'total' => Appointment::count(),
                ],
                'services' => Service::count(),
                'testimonials' => [
                    'published' => Testimonial::where('is_published', true)->count(),
                    'pending' => Testimonial::where('is_published', false)->count(),
                ],
                'recent_appointments' => AppointmentResource::collection(
                    Appointment::with('service')->latest()->take(8)->get()
                ),
            ],
        ]);
    }
}
