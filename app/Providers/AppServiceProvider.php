<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\{ Logo, SuccessStory, History };

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
            'logo' => function() {
                return Logo::latest()->first();
            },
            'success_stories' => function() {
                return SuccessStory::orderBy('created_at', 'desc')->get();
            },
            'histories' => function() {
                return History::orderBy('created_at', 'desc')->get();
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
