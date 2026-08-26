<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
    public function index()
    {
        $raw = Setting::get('page_content');

        return response()->json([
            'data' => $raw ? json_decode($raw, true) : (object) [],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'content' => ['required', 'array'],
            'content.*' => ['nullable', 'string', 'max:5000'],
        ]);

        // Drop empty values so the frontend falls back to its built-in defaults.
        $clean = array_filter(
            $data['content'],
            fn ($v) => $v !== null && trim((string) $v) !== '',
        );

        Setting::put('page_content', json_encode($clean));

        return response()->json(['data' => $clean ?: (object) []]);
    }
}
