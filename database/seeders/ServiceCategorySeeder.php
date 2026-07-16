<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Mirrors the curated catalog the site displays. Admins can edit these
        // from the dashboard; the frontend reads them live from the API.
        $categories = [
            ['name' => 'Brows', 'slug' => 'brows', 'icon' => 'sparkles', 'description' => 'Semi-permanent brow artistry — ombre, nano hair strokes and digital combination.'],
            ['name' => 'Lips', 'slug' => 'lips', 'icon' => 'heart', 'description' => 'Lip tinting and blushing for natural, healthy-looking colour.'],
            ['name' => 'Lashes', 'slug' => 'lashes', 'icon' => 'eye', 'description' => 'Korean lashliner — semi-permanent lash-line enhancement.'],
            ['name' => 'Facial Treatments', 'slug' => 'facial-treatments', 'icon' => 'leaf', 'description' => 'Cleansing, diamond peel, acne and hydra facials for healthy glowing skin.'],
            ['name' => 'Laser & Body', 'slug' => 'laser-body', 'icon' => 'wand', 'description' => 'Pico laser, diode laser, radio frequency and tattoo removal treatments.'],
        ];

        foreach ($categories as $i => $data) {
            ServiceCategory::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }
    }
}
