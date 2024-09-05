<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Inertia::share([
            'user' => function () {
                return auth()->user();
            },
            'res' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                    'message' => session('message')
                ];
            },
        ]);
    }
}
