<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Helpers\ImageHelper;

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
        // Force HTTPS in production
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
        // Register ImageHelper as a global function for Blade and Inertia
        View::share('imageUrl', function($path, $default = '/images/default.png') {
            return ImageHelper::url($path, $default);
        });

        // Register a blade directive for images
        Blade::directive('image', function ($expression) {
            return "<?php echo App\\Helpers\\ImageHelper::url($expression); ?>";
        });
    }
}
