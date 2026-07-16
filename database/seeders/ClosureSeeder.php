<?php

namespace Database\Seeders;

use App\Models\Closure;
use Illuminate\Database\Seeder;

class ClosureSeeder extends Seeder
{
    public function run(): void
    {
        // Preserve the studio's original default: closed every Monday.
        // (1 = Monday, ISO-8601.) Admins can change this from the dashboard.
        Closure::updateOrCreate(
            ['weekday' => 1],
            ['reason' => 'Weekly day off'],
        );
    }
}
