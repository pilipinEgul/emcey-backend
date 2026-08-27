<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactInquiry;

class BadgeController extends Controller
{
    /** Counts that drive the sidebar "needs attention" badges. */
    public function index()
    {
        return response()->json([
            'data' => [
                'messages' => ContactInquiry::query()->where('status', '!=', 'closed')->count(),
                'appointments' => Appointment::query()->where('status', 'pending')->count(),
            ],
        ]);
    }
}
