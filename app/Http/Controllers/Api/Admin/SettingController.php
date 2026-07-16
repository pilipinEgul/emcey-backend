<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** Booking schedule the public availability calendar is built from. */
    public function index()
    {
        return response()->json(['data' => $this->bookingSettings()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'open_hour' => ['required', 'integer', 'min:0', 'max:23'],
            'close_hour' => ['required', 'integer', 'min:1', 'max:24', 'gt:open_hour'],
            // 0 = fall back to each service's duration.
            'slot_interval_minutes' => ['required', 'integer', 'min:0', 'max:480'],
        ]);

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        return response()->json(['data' => $this->bookingSettings()]);
    }

    /** @return array{open_hour:int, close_hour:int, slot_interval_minutes:int} */
    private function bookingSettings(): array
    {
        return [
            'open_hour' => (int) Setting::get('open_hour', config('emcey.booking.open_hour')),
            'close_hour' => (int) Setting::get('close_hour', config('emcey.booking.close_hour')),
            'slot_interval_minutes' => (int) Setting::get(
                'slot_interval_minutes',
                config('emcey.booking.slot_interval_minutes'),
            ),
        ];
    }
}
