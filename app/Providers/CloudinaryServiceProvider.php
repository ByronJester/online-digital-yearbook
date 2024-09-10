<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cloudinary\Cloudinary;

class CloudinaryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Cloudinary::class, function ($app) {
            return new Cloudinary([
                'cloud' => [
                    'cloud_name' => 'dcmgsini6',
                    'api_key'    => '522938554472129',
                    'api_secret' => 'FrOIDIXbv7JfcVc9LpjZB1RMJQA',
                ],
            ]);
        });
    }
}

