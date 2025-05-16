<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Cloudinary instance
        $this->app->singleton('cloudinary', function ($app) {
            return new Cloudinary(
                Configuration::instance([
                    'cloud' => [
                        'cloud_name' => config('cloudinary.cloud_name'),
                        'api_key' => config('cloudinary.api_key'),
                        'api_secret' => config('cloudinary.api_secret'),
                        'secure' => config('cloudinary.secure', true),
                    ],
                    'url' => [
                        'secure' => config('cloudinary.secure', true),
                    ],
                ])
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
