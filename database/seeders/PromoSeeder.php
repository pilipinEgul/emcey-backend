<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        Promo::updateOrCreate(
            ['code' => 'EMCEY10'],
            [
                'title' => '10% Off Your First Brow Treatment',
                'description' => 'Welcome to Emcey Brows! Use this code on your first booking for an instant 10% off.',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 1500,
                'usage_limit' => 200,
                'is_active' => true,
                'is_featured' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonths(2),
            ]
        );

        Promo::updateOrCreate(
            ['code' => 'LIPGLOW500'],
            [
                'title' => 'Save ₱500 on Lip Blush',
                'description' => 'Enjoy ₱500 off any lip blush treatment this season.',
                'type' => 'fixed',
                'value' => 500,
                'is_active' => true,
                'is_featured' => false,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]
        );
    }
}
