<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('public-read', fn (Request $request) =>
            Limit::perMinute(120)->by($this->clientKey($request))
        );

        RateLimiter::for('availability', fn (Request $request) =>
            Limit::perMinute(60)->by($this->clientKey($request))
        );

        RateLimiter::for('booking', fn (Request $request) =>
            Limit::perMinutes(10, 5)->by($this->clientKey($request))
        );

        RateLimiter::for('contact', fn (Request $request) =>
            Limit::perMinutes(10, 5)->by($this->clientKey($request))
        );

        RateLimiter::for('login', fn (Request $request) =>
            Limit::perMinute(5)->by(
                strtolower((string) $request->input('email')) . '|' . $this->clientKey($request)
            )
        );
    }

    private function clientKey(Request $request): string
    {
        return $request->header('CF-Connecting-IP')
            ?: $request->header('X-Forwarded-For')
            ?: (string) $request->ip();
    }
}
