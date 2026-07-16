<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SiteSettingController extends Controller
{
    /**
     * Public: admin-editable business info (name, address, contact, socials).
     * Returns null when nothing has been customised — the frontend then uses
     * its built-in defaults from lib/site.ts.
     */
    public function show()
    {
        $raw = Setting::get('site_info');

        return response()->json([
            'data' => $raw ? json_decode($raw, true) : null,
        ]);
    }
}
