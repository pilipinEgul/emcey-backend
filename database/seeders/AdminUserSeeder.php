<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@emceybrows.test');
        $name = env('ADMIN_NAME', 'Emcey Studio');
        $password = env('ADMIN_PASSWORD', 'password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user ready:');
        $this->command->line("  email: {$user->email}");

        if (app()->environment('local', 'testing') && $password === 'password') {
            $this->command->warn('  password: password  (default — change for production)');
        } else {
            $this->command->line('  password: (from ADMIN_PASSWORD env)');
        }
    }
}
