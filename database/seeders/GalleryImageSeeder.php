<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\Service;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all()->keyBy('slug');

        $items = [
            [
                'slug' => 'microblading',
                'category' => 'Brows',
                'title' => 'Microblading — Bea M.',
                'alt_text' => 'Microblading before and after, hair-stroke brows, Imus Cavite client',
            ],
            [
                'slug' => 'microblading',
                'category' => 'Brows',
                'title' => 'Microblading — Cami R.',
                'alt_text' => 'Microblading result, natural hair-stroke brows by Emcey Brows Imus Cavite',
            ],
            [
                'slug' => 'ombre-brows',
                'category' => 'Brows',
                'title' => 'Ombre Brows — Hannah G.',
                'alt_text' => 'Ombre powder brows before and after, soft gradient finish, Imus Cavite',
            ],
            [
                'slug' => 'lip-blush',
                'category' => 'Lips',
                'title' => 'Lip Blush — Camille R.',
                'alt_text' => 'Lip blush before and after, soft tinted lips, Cavite client',
            ],
            [
                'slug' => 'lip-blush',
                'category' => 'Lips',
                'title' => 'Lip Blush — Jen V.',
                'alt_text' => 'Lip blush result, custom rose tint by Emcey Brows Imus Cavite',
            ],
            [
                'slug' => 'eyelash-extensions',
                'category' => 'Lashes',
                'title' => 'Volume Lashes — Jelai P.',
                'alt_text' => 'Volume eyelash extensions, custom lash map, Imus Cavite lash studio',
            ],
            [
                'slug' => 'lash-lift',
                'category' => 'Lashes',
                'title' => 'Lash Lift — Mae S.',
                'alt_text' => 'Lash lift before and after, doll-curl natural lashes, Imus Cavite',
            ],
            [
                'slug' => 'bb-glow-facial',
                'category' => 'Facial Treatments',
                'title' => 'BB Glow — Mara D.',
                'alt_text' => 'BB Glow facial result, glass-skin glow, Imus Cavite beauty clinic',
            ],
        ];

        foreach ($items as $i => $item) {
            $service = $services->get($item['slug']);
            GalleryImage::updateOrCreate(
                ['title' => $item['title']],
                [
                    'service_id' => $service?->id,
                    'category' => $item['category'],
                    'alt_text' => $item['alt_text'],
                    'image_path' => "gallery/placeholder-{$i}.jpg",
                    'is_featured' => $i < 3,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]
            );
        }
    }
}
