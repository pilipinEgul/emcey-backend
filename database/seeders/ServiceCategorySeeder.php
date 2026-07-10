<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Brows', 'slug' => 'brows', 'icon' => 'sparkles', 'description' => 'Microblading, ombre brows, brow lamination and shaping.'],
            ['name' => 'Lips', 'slug' => 'lips', 'icon' => 'heart', 'description' => 'Lip blush, lip neutralisation and lip enhancement.'],
            ['name' => 'Lashes', 'slug' => 'lashes', 'icon' => 'eye', 'description' => 'Volume lashes, classic extensions and lash lifts.'],
            ['name' => 'Facial Treatments', 'slug' => 'facial-treatments', 'icon' => 'leaf', 'description' => 'Glow facials, acne care, BB Glow and rejuvenating treatments.'],
            ['name' => 'Permanent Makeup', 'slug' => 'permanent-makeup', 'icon' => 'wand', 'description' => 'Long-lasting semi-permanent makeup tailored to you.'],
        ];

        foreach ($categories as $i => $data) {
            ServiceCategory::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }
    }
}
