<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $raw = Setting::get('theme');

        return response()->json(['data' => $raw ? json_decode($raw, true) : null]);
    }

    public function update(Request $request)
    {
        $hex = ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'];

        $data = $request->validate([
            'default_mode' => ['required', 'in:light,dark,system'],
            'light' => ['required', 'array'],
            'light.bg' => $hex,
            'light.header' => $hex,
            'light.primary' => $hex,
            'light.text' => $hex,
            'dark' => ['required', 'array'],
            'dark.bg' => $hex,
            'dark.header' => $hex,
            'dark.primary' => $hex,
            'dark.text' => $hex,
        ]);

        Setting::put('theme', json_encode($data));

        return response()->json(['data' => $data]);
    }
}
