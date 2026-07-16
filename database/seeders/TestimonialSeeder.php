<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Intentionally empty. The site sources its reviews from Google (live,
     * read-only via the Places API) and Facebook (link-out) — it no longer
     * displays self-hosted testimonials, so we do not seed any placeholder
     * reviews. See docs/TEST-ACCOUNT-SETUP.md and the frontend
     * docs/REVIEWS-INTEGRATION.md.
     */
    public function run(): void
    {
        // no-op
    }
}
