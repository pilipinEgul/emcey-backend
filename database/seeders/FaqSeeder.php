<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Service;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all()->keyBy('slug');

        $general = [
            ['Where is Emcey Brows Aesthetics located?', 'We are located in Imus, Cavite. The full address and map are on our Contact page.'],
            ['Do you accept walk-ins?', 'Yes, but we strongly recommend booking online so we can prepare your time slot and tools.'],
            ['Do you offer down payments?', 'Yes. A small reservation fee secures your booking and is deducted from your final bill.'],
            ['Is the studio hygienic?', 'Absolutely. We use single-use needles, sterilised tools and follow medical-grade hygiene standards.'],
            ['Do you have a payment plan?', 'We accept GCash, Maya, cash and partial down payment online.'],
        ];

        foreach ($general as $i => [$q, $a]) {
            Faq::updateOrCreate(
                ['question' => $q],
                ['answer' => $a, 'category' => 'General', 'sort_order' => $i + 1, 'is_active' => true]
            );
        }

        $perService = [
            'microblading' => [
                ['Does microblading hurt?', 'We use a topical numbing cream so most clients feel only light pressure.'],
                ['How long does microblading last?', 'Typically 12–18 months. We recommend a retouch every 6–12 months.'],
                ['Can I get microblading if I have oily skin?', 'For very oily skin we usually recommend Ombre Brows for crisper, longer-lasting results.'],
            ],
            'lip-blush' => [
                ['Will my lips look red right after?', 'Yes — colour appears brighter for 3–5 days, then softens to your customised shade.'],
                ['Is lip blush safe for sensitive lips?', 'Yes. We use vegan, hypoallergenic pigments and screen your skin during consult.'],
            ],
        ];

        foreach ($perService as $slug => $items) {
            $service = $services->get($slug);
            if (! $service) continue;
            foreach ($items as $i => [$q, $a]) {
                Faq::updateOrCreate(
                    ['question' => $q, 'service_id' => $service->id],
                    ['answer' => $a, 'sort_order' => $i + 1, 'is_active' => true]
                );
            }
        }
    }
}
