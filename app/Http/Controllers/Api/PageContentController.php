<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class PageContentController extends Controller
{
    /** Public: admin-edited page content (key => value). Empty = use frontend defaults. */
    public function show()
    {
        $raw = Setting::get('page_content');

        return response()->json([
            'data' => $raw ? json_decode($raw, true) : (object) [],
        ]);
    }
}
