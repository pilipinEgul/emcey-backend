<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $cats = ServiceCategory::pluck('id', 'slug');

        $services = [
            [
                'name' => 'Ombre Microshading Brows', 'slug' => 'ombre-microshading', 'category' => 'brows', 'is_featured' => true,
                'short_description' => 'Soft powdered brows with a flawless ombre gradient.',
                'description' => 'Ombre Microshading creates a soft, powdered brow that fades from a subtle front to a crisp tail — like a polished daily makeup look that never washes off. Done in two sessions (initial + perfecting), with a 3-month to 1-year touch-up window.',
                'price' => 7000, 'promo_price' => 2999,
                'sr_artist_first_session' => 2999, 'master_artist_first_session' => 4499, 'sr_artist_second_session' => 1500, 'master_artist_second_session' => 2500,
                'duration_minutes' => 180, 'cover_image' => '/images/666957526_1188211739950060_400784778358443402_n.jpg',
                'benefits' => ['Soft powdered finish', 'Ideal for oily skin', 'Two-session protocol (1st + 2nd)', 'Touch-up from ₱1,999 (3 months – 1 year)'],
                'process_steps' => ['Consultation & shape mapping', 'Topical numbing', '1st session pigment work', '2nd session perfecting (after 4–6 weeks)'],
                'aftercare' => ['Avoid water on brows for 7 days', 'No makeup on the area for 10 days', 'Apply healing balm twice daily', 'Book your 2nd session in 4–6 weeks'],
                'meta_title' => 'Ombre Microshading Brows in Imus Cavite | Emcey Brows',
                'meta_description' => 'Ombre microshading brows in Imus, Cavite. 1st session from ₱2,999. Soft powdered finish, touch-up from ₱1,999.',
            ],
            [
                'name' => 'Digital Nano Hair Strokes', 'slug' => 'digital-nano-hair-strokes', 'category' => 'brows', 'is_featured' => true,
                'short_description' => 'Hyper-realistic digital hair-stroke brows, stroke by stroke.',
                'description' => 'Digital Nano Hair Strokes use a precision digital machine to draw ultra-fine, hair-like strokes for the most natural brow finish we offer. Best for normal-to-dry skin and clients who want the look of real brow hairs.',
                'price' => 10000, 'promo_price' => 5999,
                'sr_artist_first_session' => 5999, 'master_artist_first_session' => 5999, 'sr_artist_second_session' => 3250, 'master_artist_second_session' => 3250,
                'duration_minutes' => 180, 'cover_image' => '/images/671149180_3306834596163166_5499780834327785454_n.jpg',
                'benefits' => ['Most natural hair-stroke realism', 'Digital precision machine', 'Two-session protocol', 'Touch-up from ₱3,499 (3 months – 1 year)'],
                'process_steps' => ['Consultation & shape mapping', 'Topical numbing', '1st session digital strokes', '2nd session perfecting (after 4–6 weeks)'],
                'aftercare' => ['Avoid water on brows for 7 days', 'No makeup on the area for 10 days', 'Apply healing balm twice daily', 'Book your 2nd session in 4–6 weeks'],
                'meta_title' => 'Digital Nano Hair Strokes Brows in Imus | Emcey Brows Aesthetics',
                'meta_description' => 'Digital nano hair-stroke brows in Imus, Cavite. 1st session from ₱4,499. Hyper-realistic hair-stroke finish.',
            ],
            [
                'name' => 'Digital Combination Brows', 'slug' => 'digital-combination-brows', 'category' => 'brows', 'is_featured' => true,
                'short_description' => 'Hair strokes at the front, ombre at the tail — best of both.',
                'description' => 'Digital Combination Brows pair nano hair strokes at the head with a soft ombre shade at the tail — the most flexible result for clients who want defined yet natural brows that suit any skin type.',
                'price' => 8000, 'promo_price' => 3699,
                'sr_artist_first_session' => 3699, 'master_artist_first_session' => 5199, 'sr_artist_second_session' => 1500, 'master_artist_second_session' => 2500,
                'duration_minutes' => 200, 'cover_image' => '/images/670304583_1345528570977679_8758680301644996539_n.jpg',
                'benefits' => ['Hair strokes + ombre shading', 'Suits most skin types', 'Two-session protocol', 'Touch-up from ₱2,699 (3 months – 1 year)'],
                'process_steps' => ['Consultation & shape mapping', 'Topical numbing', '1st session strokes + shading', '2nd session perfecting (after 4–6 weeks)'],
                'aftercare' => ['Avoid water on brows for 7 days', 'No makeup on the area for 10 days', 'Apply healing balm twice daily', 'Book your 2nd session in 4–6 weeks'],
                'meta_title' => 'Digital Combination Brows in Imus Cavite | Emcey Brows',
                'meta_description' => 'Digital combination brows in Imus, Cavite. 1st session from ₱3,699. Hair strokes + ombre — best of both.',
            ],
            [
                'name' => 'Lip Tinting / Blushing', 'slug' => 'lip-tinting-blushing', 'category' => 'lips', 'is_featured' => true,
                'short_description' => 'Soft semi-permanent lip tint with a natural, healthy flush.',
                'description' => 'Lip Tinting (Blushing) evens out your natural lip tone and adds a soft, custom blush colour for a healthy, kissed look. Custom shade matched to your skin tone — completed in two sessions.',
                'price' => 7000, 'promo_price' => 2999,
                'sr_artist_first_session' => 2999, 'master_artist_first_session' => 4499, 'sr_artist_second_session' => 1500, 'master_artist_second_session' => 2500,
                'duration_minutes' => 180, 'cover_image' => '/images/671365357_1652344876110909_2196230468653312860_n.jpg',
                'benefits' => ['Even, healthy lip tone', 'Custom blush shade', 'Two-session protocol', 'Touch-up from ₱1,999 (3 months – 1 year)'],
                'process_steps' => ['Shade consultation', 'Topical numbing', '1st session pigment work', '2nd session perfecting (after 4–6 weeks)'],
                'aftercare' => ['Keep lips dry for the first 24 hours', 'Apply healing balm hourly for 3 days', 'Avoid spicy / hot foods for 7 days', 'Book your 2nd session in 4–6 weeks'],
                'meta_title' => 'Lip Tinting & Blushing in Imus Cavite | Emcey Brows',
                'meta_description' => 'Semi-permanent lip tinting / blushing in Imus, Cavite. 1st session from ₱2,999. Custom blush shade.',
            ],
            [
                'name' => 'Korean Lashliner', 'slug' => 'korean-lashliner', 'category' => 'lashes', 'is_featured' => true,
                'short_description' => 'Subtle semi-permanent eyeliner along the lash line.',
                'description' => 'Korean Lashliner sits at the base of your lashes to create the illusion of fuller, more defined lashes — without daily eyeliner. Soft, natural and wakeup-ready.',
                'price' => 4000, 'promo_price' => 1999,
                'sr_artist_first_session' => 1999, 'master_artist_first_session' => 3499, 'sr_artist_second_session' => 1500, 'master_artist_second_session' => 2500,
                'duration_minutes' => 120, 'cover_image' => '/images/668042950_1019368564256754_3153242901631791560_n.jpg',
                'benefits' => ['Defined lash line, no daily liner', 'Soft natural finish', 'Two-session protocol', 'Touch-up from ₱1,999 (3 months – 1 year)'],
                'process_steps' => ['Eye-shape consultation', 'Topical numbing', '1st session lash-line pigment', '2nd session perfecting (after 4–6 weeks)'],
                'aftercare' => ['Keep the area dry for 5 days', 'No eye makeup for 7 days', 'Apply healing balm twice daily', 'Book your 2nd session in 4–6 weeks'],
                'meta_title' => 'Korean Lashliner in Imus Cavite | Semi-Permanent Eyeliner',
                'meta_description' => 'Korean lashliner in Imus, Cavite. 1st session from ₱1,999. Subtle semi-permanent eyeliner along the lash line.',
            ],
            [
                'name' => 'Cleansing Facial', 'slug' => 'cleansing-facial', 'category' => 'facial-treatments', 'is_featured' => false,
                'short_description' => 'Gentle deep-cleanse facial for healthy, refreshed skin.',
                'description' => 'A foundational deep-cleanse facial — exfoliation, gentle extraction and a soothing finish. The starting point for clearer, brighter skin.',
                'price' => 500, 'promo_price' => null,
                'duration_minutes' => 45, 'cover_image' => '/images/705153856_26560352726993811_6455456721081485987_n.jpg',
                'benefits' => ['Deep pore cleansing', 'Gentle exfoliation', 'Soothing finish'],
                'meta_title' => 'Cleansing Facial in Imus Cavite | Emcey Brows Aesthetics',
                'meta_description' => 'Gentle deep-cleansing facial in Imus, Cavite for clearer, refreshed skin.',
            ],
            [
                'name' => 'Diamond Peel Facial', 'slug' => 'diamond-peel-facial', 'category' => 'facial-treatments', 'is_featured' => false,
                'short_description' => 'Crystal-free microdermabrasion for instantly smoother skin.',
                'description' => 'Diamond Peel is a crystal-free microdermabrasion that gently buffs the top layer of skin — revealing brighter tone and softer texture immediately after.',
                'price' => 700, 'promo_price' => null,
                'duration_minutes' => 60, 'cover_image' => '/images/705153856_26560352726993811_6455456721081485987_n.jpg',
                'benefits' => ['Smoother texture', 'Brighter tone', 'No downtime'],
                'meta_title' => 'Diamond Peel Facial in Imus Cavite | Crystal-Free Microdermabrasion',
                'meta_description' => 'Diamond peel facial in Imus, Cavite. Crystal-free microdermabrasion for smoother, brighter skin.',
            ],
            [
                'name' => 'Acne Facial', 'slug' => 'acne-facial', 'category' => 'facial-treatments', 'is_featured' => false,
                'short_description' => 'Targeted facial for active breakouts and oily skin.',
                'description' => 'Targeted acne-care protocol for active breakouts and oily, congested skin — gentle deep cleansing, extraction and soothing finish designed for the humid Cavite climate.',
                'price' => 1200, 'promo_price' => null,
                'duration_minutes' => 75, 'cover_image' => '/images/705153856_26560352726993811_6455456721081485987_n.jpg',
                'benefits' => ['Calms active breakouts', 'Deep pore cleansing', 'Suits humid-climate skin'],
                'meta_title' => 'Acne Facial in Imus Cavite | Deep-Cleansing Treatment',
                'meta_description' => 'Acne facial in Imus, Cavite. A gentle, effective protocol for active breakouts.',
            ],
            [
                'name' => 'Hydra Facial', 'slug' => 'hydra-facial', 'category' => 'facial-treatments', 'is_featured' => true,
                'short_description' => 'Hydration-boosting facial for dewy, glass-skin glow.',
                'description' => 'A multi-step hydra facial that cleanses, exfoliates and floods the skin with hydration for an immediate dewy, glass-skin glow. Suits dehydrated and dull skin.',
                'price' => 1500, 'promo_price' => null,
                'duration_minutes' => 75, 'cover_image' => '/images/705153856_26560352726993811_6455456721081485987_n.jpg',
                'benefits' => ['Deep hydration', 'Glass-skin glow', 'Plumps fine lines'],
                'meta_title' => 'Hydra Facial in Imus Cavite | Hydration Glow Treatment',
                'meta_description' => 'Hydra facial in Imus, Cavite. Hydration-boosting facial for a dewy glass-skin glow.',
            ],
            [
                'name' => 'Diode Laser Hair Removal', 'slug' => 'diode-laser-hair-removal', 'category' => 'laser-body', 'is_featured' => false,
                'short_description' => 'Permanent hair reduction — underarm, legs, bikini and more.',
                'description' => 'Medical-grade diode laser hair removal for long-term hair reduction. Buy-5-get-2-free per area, with a 10+5-free VIP option. Pricing varies by area — underarm from ₱500, full legs from ₱1,200.',
                'price' => 350, 'promo_price' => null,
                'duration_minutes' => 30, 'cover_image' => '/images/706473564_1389681486304309_8944739647048307489_n.jpg',
                'benefits' => ['Underarm — ₱500', 'Upper Lip — ₱350', 'Half Legs — ₱950', 'Full Legs — ₱1,200', 'Bikini — ₱650 · Brazilian — ₱1,000', 'BUY 5 + GET 2 FREE · VIP: BUY 10 + 5 FREE'],
                'meta_title' => 'Diode Laser Hair Removal in Imus Cavite | Emcey Brows',
                'meta_description' => 'Medical-grade diode laser hair removal in Imus, Cavite. From ₱350. Buy 5 + get 2 free per area.',
            ],
            [
                'name' => 'Pico Laser Treatments', 'slug' => 'pico-laser-treatments', 'category' => 'laser-body', 'is_featured' => true,
                'short_description' => 'Carbon laser, melasma removal and whitening treatments.',
                'description' => 'Pico laser treatments target melasma, dark spots and uneven tone. Includes carbon laser face peel, underarm and elbow whitening, and groin (singit) whitening.',
                'price' => 700, 'promo_price' => null,
                'duration_minutes' => 30, 'cover_image' => '/images/706473564_1389681486304309_8944739647048307489_n.jpg',
                'benefits' => ['Black Doll Carbon Laser (Face) — ₱900', 'Melasma / Dark Spots Removal — ₱900', 'UA Whitening Carbon Laser — ₱700', 'Elbow Whitening Laser — ₱700', 'Groin (Singit) Whitening Laser — ₱800', 'BUY 5 + GET 2 FREE · VIP: BUY 10 + 5 FREE'],
                'meta_title' => 'Pico Laser Treatments in Imus Cavite | Carbon Laser, Whitening',
                'meta_description' => 'Pico laser treatments in Imus, Cavite. Carbon laser, melasma removal and underarm/elbow whitening from ₱700.',
            ],
            [
                'name' => 'Radio Frequency Treatments', 'slug' => 'radio-frequency-treatments', 'category' => 'laser-body', 'is_featured' => false,
                'short_description' => 'Non-surgical skin tightening for face and body.',
                'description' => 'Radio frequency treatments tighten and contour without surgery. Targets double chin, full face, arms and tummy — buy-5-get-2-free per area, with a 10+5-free VIP option.',
                'price' => 350, 'promo_price' => null,
                'duration_minutes' => 30, 'cover_image' => '/images/706473564_1389681486304309_8944739647048307489_n.jpg',
                'benefits' => ['Double Chin — ₱350', 'Full Face — ₱500', 'Arms — ₱700', 'Tummy — ₱1,000', 'BUY 5 + GET 2 FREE · VIP: BUY 10 + 5 FREE'],
                'meta_title' => 'Radio Frequency Treatments in Imus Cavite | Skin Tightening',
                'meta_description' => 'Radio frequency skin tightening in Imus, Cavite. Double chin, full face, arms and tummy from ₱350.',
            ],
            [
                'name' => 'Tattoo & Mole Removal', 'slug' => 'tattoo-mole-removal', 'category' => 'laser-body', 'is_featured' => false,
                'short_description' => 'Eyebrow tattoo, body tattoo, warts and milia removal.',
                'description' => 'Safe removal of unwanted eyebrow tattoos, body tattoos, warts and milia using electrocautery and tattoo-specific laser protocols.',
                'price' => 250, 'promo_price' => null,
                'duration_minutes' => 30, 'cover_image' => '/images/706473564_1389681486304309_8944739647048307489_n.jpg',
                'benefits' => ['Eyebrow Tattoo Removal — ₱1,000', 'Body Tattoo (per area) — ₱1,200', 'Warts (per piece, big) — ₱300', 'Warts (Unlimited Face & Neck) — ₱2,500', 'Milia Removal (per pc) — ₱250'],
                'meta_title' => 'Tattoo & Mole Removal in Imus Cavite | Emcey Brows',
                'meta_description' => 'Eyebrow tattoo removal, body tattoo, warts and milia removal in Imus, Cavite.',
            ],
        ];

        foreach ($services as $i => $s) {
            Service::updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'service_category_id' => $cats[$s['category']] ?? null,
                    'name' => $s['name'],
                    'short_description' => $s['short_description'],
                    'description' => $s['description'],
                    'price' => $s['price'],
                    'promo_price' => $s['promo_price'] ?? null,
                    'sr_artist_first_session' => $s['sr_artist_first_session'] ?? null,
                    'master_artist_first_session' => $s['master_artist_first_session'] ?? null,
                    'sr_artist_second_session' => $s['sr_artist_second_session'] ?? null,
                    'master_artist_second_session' => $s['master_artist_second_session'] ?? null,
                    'duration_minutes' => $s['duration_minutes'],
                    'cover_image' => $s['cover_image'] ?? null,
                    'benefits' => $s['benefits'] ?? null,
                    'process_steps' => $s['process_steps'] ?? null,
                    'aftercare' => $s['aftercare'] ?? null,
                    'meta_title' => $s['meta_title'] ?? null,
                    'meta_description' => $s['meta_description'] ?? null,
                    'is_featured' => $s['is_featured'] ?? false,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]
            );
        }
    }
}
