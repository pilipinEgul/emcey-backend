<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ClosureSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            GalleryImageSeeder::class,
            PromoSeeder::class,
        ]);
    }
}
