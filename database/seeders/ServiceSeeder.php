<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $byCategory = ServiceCategory::all()->keyBy('slug');

        $services = [
            [
                'category' => 'brows',
                'name' => 'Microblading',
                'slug' => 'microblading',
                'short_description' => 'Hyper-realistic hair-stroke brows tailored to your face shape.',
                'description' => "Microblading is a semi-permanent technique that creates natural, hair-like strokes for fuller and beautifully defined brows. Each session begins with a careful consultation to map your ideal shape based on your facial proportions and personal style.",
                'price' => 5500,
                'promo_price' => 4500,
                'duration_minutes' => 150,
                'benefits' => ['Hair-stroke realism', 'Lasts 12-18 months', 'Customised shape', 'Painless numbing'],
                'process_steps' => ['Consultation & shape mapping', 'Numbing', 'Pigment work', 'Aftercare briefing'],
                'aftercare' => ['Avoid water on brows for 7 days', 'No makeup on the area for 10 days', 'Apply healing balm twice daily', 'Schedule your retouch in 6 weeks'],
                'meta_title' => 'Microblading in Imus Cavite | Emcey Brows Aesthetics',
                'meta_description' => 'Premium microblading in Imus Cavite. Hair-stroke brows, custom shape mapping, gentle numbing and a complete aftercare plan.',
                'is_featured' => true,
            ],
            [
                'category' => 'brows',
                'name' => 'Ombre Brows',
                'slug' => 'ombre-brows',
                'short_description' => 'Soft powder-finish brows with a flawless gradient.',
                'description' => "Ombre brows give a powdered makeup look that fades from soft at the front to crisp at the tail. Perfect for oily skin and clients who love that polished, just-filled brow finish.",
                'price' => 6500,
                'duration_minutes' => 180,
                'benefits' => ['Soft powdered finish', 'Long-lasting up to 2 years', 'Ideal for oily skin', 'Smudge-proof every day'],
                'meta_title' => 'Ombre Brows Imus | Powder Brows Cavite',
                'meta_description' => 'Ombre powder brows in Imus, Cavite. Long-lasting, smudge-proof brows with a refined gradient finish.',
                'is_featured' => true,
            ],
            [
                'category' => 'brows',
                'name' => 'Brow Lamination',
                'slug' => 'brow-lamination',
                'short_description' => 'Lifted, fluffy brows that stay put for weeks.',
                'description' => 'Brow lamination restructures the brow hairs to sit in a uniform, upward direction for a fuller, lifted finish. Perfect for clients with unruly brows or anyone after the trending fluffy-brow look without commitment.',
                'price' => 1500,
                'duration_minutes' => 60,
                'benefits' => ['Fluffy lifted look', 'Lasts 6-8 weeks', 'No downtime'],
                'meta_title' => 'Brow Lamination in Imus Cavite | Emcey Brows Aesthetics',
                'meta_description' => 'Brow lamination in Imus, Cavite. Fluffy lifted brows that hold their shape for 6–8 weeks, no downtime.',
            ],
            [
                'category' => 'lips',
                'name' => 'Lip Blush',
                'slug' => 'lip-blush',
                'short_description' => 'Soft tinted lips with a natural, kissed finish.',
                'description' => 'Lip blush enhances your natural lip colour with a soft, blush-like tint. It evens tone, redefines shape and gives your lips a beautifully balanced finish.',
                'price' => 7500,
                'promo_price' => 6500,
                'duration_minutes' => 180,
                'benefits' => ['Even, healthy tone', 'Custom blush shade', 'Subtle shape correction', 'Lasts 1-2 years'],
                'meta_title' => 'Lip Blush Imus | Lip Tattoo Cavite',
                'meta_description' => 'Custom lip blush in Imus, Cavite. A soft, natural tint that enhances your lip colour and shape.',
                'is_featured' => true,
            ],
            [
                'category' => 'lashes',
                'name' => 'Eyelash Extensions',
                'slug' => 'eyelash-extensions',
                'short_description' => 'Featherlight volume or classic lashes for everyday glam.',
                'description' => 'Custom-mapped eyelash extensions for a wakeup-ready, polished look. Choose classic, hybrid, or volume sets — each lash applied one-by-one to your natural lashes for a comfortable, flake-free finish.',
                'price' => 1200,
                'duration_minutes' => 120,
                'benefits' => ['Wakeup-ready look', 'Customised lash map', 'Lasts up to 4 weeks with refills'],
                'meta_title' => 'Eyelash Extensions in Imus Cavite | Volume & Classic Lashes',
                'meta_description' => 'Custom eyelash extensions in Imus, Cavite. Featherlight classic or volume lashes, lasting up to 4 weeks with refills.',
            ],
            [
                'category' => 'lashes',
                'name' => 'Lash Lift',
                'slug' => 'lash-lift',
                'short_description' => 'Naturally lifted lashes with a soft, doll-like curl.',
                'description' => 'A keratin lash lift gives your own lashes a soft upward curl that lasts weeks — no extensions, no glue, no daily curlers. Ideal for clients who want a more open, awake eye with zero maintenance.',
                'price' => 900,
                'duration_minutes' => 75,
                'benefits' => ['Low maintenance', 'Lasts 6-8 weeks', 'No extensions needed'],
                'meta_title' => 'Lash Lift in Imus Cavite | Natural Curled Lashes',
                'meta_description' => 'Keratin lash lift in Imus, Cavite. A naturally lifted, doll-like curl that lasts 6–8 weeks — no extensions needed.',
            ],
            [
                'category' => 'facial-treatments',
                'name' => 'BB Glow Facial',
                'slug' => 'bb-glow-facial',
                'short_description' => 'Luminous, even-toned skin with a semi-permanent foundation effect.',
                'description' => 'BB Glow infuses a custom-tinted serum into the upper skin layer for a luminous, even-toned glass-skin finish. Brightens dull skin, evens out tone, and gives a soft semi-permanent foundation effect.',
                'price' => 2500,
                'duration_minutes' => 90,
                'benefits' => ['Glass-skin glow', 'Evens tone', 'Hydrates and brightens'],
                'meta_title' => 'BB Glow Facial in Imus Cavite | Glass-Skin Treatment',
                'meta_description' => 'BB Glow facial in Imus, Cavite. Luminous, even-toned glass-skin glow with a semi-permanent foundation finish.',
            ],
            [
                'category' => 'facial-treatments',
                'name' => 'Acne Care Facial',
                'slug' => 'acne-care-facial',
                'short_description' => 'Deep-cleansing facial designed for active breakouts.',
                'description' => 'Targeted acne-care protocol for active breakouts and oily, congested skin. Gentle deep cleansing, extraction, and soothing finish — designed for the humid Cavite climate.',
                'price' => 1800,
                'duration_minutes' => 75,
                'benefits' => ['Calms active breakouts', 'Deep pore cleansing', 'Suits oily, humid-climate skin'],
                'meta_title' => 'Acne Care Facial in Imus Cavite | Deep-Cleansing Treatment',
                'meta_description' => 'Deep-cleansing acne facial in Imus, Cavite. A gentle, effective protocol for active breakouts in the Cavite climate.',
            ],
            [
                'category' => 'permanent-makeup',
                'name' => 'Permanent Eyeliner',
                'slug' => 'permanent-eyeliner',
                'short_description' => 'Defined, wake-up-ready eyes — no smudging, ever.',
                'description' => 'Permanent eyeliner gives you defined, wake-up-ready eyes with no daily smudging. We tailor thickness and finish to your eye shape — from a soft lash enhancement to a crisp wing.',
                'price' => 6000,
                'duration_minutes' => 150,
                'benefits' => ['Smudge-proof', 'Custom thickness', 'Lasts up to 2 years'],
                'meta_title' => 'Permanent Eyeliner in Imus Cavite | Smudge-Proof Liner',
                'meta_description' => 'Permanent eyeliner in Imus, Cavite. Custom thickness, smudge-proof every day, lasting up to 2 years.',
            ],
        ];

        foreach ($services as $i => $data) {
            $category = $byCategory->get($data['category']);
            unset($data['category']);

            Service::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'service_category_id' => $category?->id,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ])
            );
        }
    }
}
