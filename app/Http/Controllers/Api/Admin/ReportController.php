<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class ReportController extends Controller
{
    /** Monthly booking counts by status for the last 6 months. */
    public function bookings()
    {
        $statuses = [
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'pending' => 'Pending',
        ];

        // Six month buckets, oldest → current.
        $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        $start = $months->first();

        $byMonth = Appointment::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at', 'status'])
            ->groupBy(fn ($a) => $a->created_at->format('Y-m'));

        $series = [];
        foreach ($statuses as $key => $label) {
            $series[] = [
                'name' => $label,
                'values' => $months->map(
                    fn ($m) => $byMonth->get($m->format('Y-m'), collect())->where('status', $key)->count(),
                )->all(),
            ];
        }

        return response()->json([
            'data' => [
                'months' => $months->map(fn ($m) => $m->format('M'))->all(),
                'series' => $series,
            ],
        ]);
    }
}
