<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('student-login', function (Request $request) {
            $limiterKey = Str::transliterate(
                Str::lower($request->string('nis')).'|'.$request->ip(),
            );

            return Limit::perMinute(5)->by($limiterKey);
        });
    }
}
