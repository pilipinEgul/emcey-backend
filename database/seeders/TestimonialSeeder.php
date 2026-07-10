<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all()->keyBy('slug');

        $reviews = [
            ['name' => 'Bea M.', 'title' => 'Imus, Cavite', 'service' => 'microblading', 'rating' => 5, 'quote' => 'Sobrang ganda ng results — natural and very precise. Solid service from start to finish.'],
            ['name' => 'Camille R.', 'title' => 'Bacoor', 'service' => 'lip-blush', 'rating' => 5, 'quote' => 'Best decision I made. My lips look fresh and tinted even without makeup.'],
            ['name' => 'Hannah G.', 'title' => 'Dasmariñas', 'service' => 'ombre-brows', 'rating' => 5, 'quote' => 'Clean studio, very professional artist, and the results last. Worth every peso.'],
            ['name' => 'Jelai P.', 'title' => 'General Trias', 'service' => 'eyelash-extensions', 'rating' => 5, 'quote' => 'My lashes look so fluffy and feel super light. Will keep coming back.'],
            ['name' => 'Mara D.', 'title' => 'Imus, Cavite', 'service' => 'bb-glow-facial', 'rating' => 5, 'quote' => 'Glowy skin for weeks — easily my favourite facial in Cavite.'],
            ['name' => 'Ria S.', 'title' => 'Imus, Cavite', 'service' => 'brow-lamination', 'rating' => 5, 'quote' => 'Brows look thick and lifted every morning. Super low maintenance.'],
        ];

        foreach ($reviews as $i => $r) {
            Testimonial::updateOrCreate(
                ['client_name' => $r['name'], 'quote' => $r['quote']],
                [
                    'service_id' => $services->get($r['service'])?->id,
                    'client_title' => $r['title'],
                    'rating' => $r['rating'],
                    'source' => 'Google',
                    'is_featured' => $i < 3,
                    'is_published' => true,
                    'sort_order' => $i + 1,
                ]
            );
        }
    }
}
