<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class ThemeController extends Controller
{
    /** Public: admin-chosen theme (colors + default mode). Null = frontend defaults. */
    public function show()
    {
        $raw = Setting::get('theme');

        return response()->json([
            'data' => $raw ? json_decode($raw, true) : null,
        ]);
    }
}
