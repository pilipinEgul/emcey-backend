<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactInquiry;
use App\Models\GalleryImage;
use App\Models\Promo;
use App\Models\Service;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            ['label' => 'Services', 'value' => Service::count(), 'route' => 'admin.services.index', 'tone' => 'terracotta'],
            ['label' => 'Testimonials', 'value' => Testimonial::count(), 'route' => 'admin.testimonials.index', 'tone' => 'blush'],
            ['label' => 'Gallery items', 'value' => GalleryImage::count(), 'route' => 'admin.gallery.index', 'tone' => 'nude'],
            ['label' => 'Active promos', 'value' => Promo::where('is_active', true)->count(), 'route' => 'admin.promos.index', 'tone' => 'terracotta'],
            ['label' => 'Pending bookings', 'value' => Appointment::where('status', 'pending')->count(), 'route' => 'admin.appointments.index', 'tone' => 'gold'],
            ['label' => 'New inquiries', 'value' => ContactInquiry::where('status', 'new')->count(), 'route' => 'admin.inquiries.index', 'tone' => 'blush'],
        ];

        $recentAppointments = Appointment::with('service')
            ->latest('scheduled_at')
            ->take(6)
            ->get();

        $recentInquiries = ContactInquiry::latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments', 'recentInquiries'));
    }
}
