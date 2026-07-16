<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        return response()->json(['data' => $this->current()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'shortName' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],

            'address' => ['nullable', 'array'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:255'],
            'address.region' => ['nullable', 'string', 'max:255'],
            'address.country' => ['nullable', 'string', 'max:255'],
            'address.postalCode' => ['nullable', 'string', 'max:32'],

            'contact' => ['nullable', 'array'],
            'contact.phone' => ['nullable', 'string', 'max:64'],
            'contact.phoneTel' => ['nullable', 'string', 'max:64'],
            'contact.landline' => ['nullable', 'string', 'max:64'],
            'contact.landlineTel' => ['nullable', 'string', 'max:64'],
            'contact.email' => ['nullable', 'email', 'max:255'],
            'contact.bookingHours' => ['nullable', 'string', 'max:255'],

            'socials' => ['nullable', 'array'],
            'socials.facebook' => ['nullable', 'string', 'max:500'],
            'socials.instagram' => ['nullable', 'string', 'max:500'],
            'socials.googleMaps' => ['nullable', 'string', 'max:1000'],
        ]);

        Setting::put('site_info', json_encode($data));

        return response()->json(['data' => $this->current()]);
    }

    private function current(): ?array
    {
        $raw = Setting::get('site_info');

        return $raw ? json_decode($raw, true) : null;
    }
}
