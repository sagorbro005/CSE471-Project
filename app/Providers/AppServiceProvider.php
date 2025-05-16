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
        
        // Enable query caching for improved performance
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\DB::connection()->enableQueryLog();
        }
        
        // Set reasonable limits for eager loading to prevent N+1 query issues
        \Illuminate\Database\Eloquent\Builder::macro('eagerLimit', function ($relations, $limit = 10) {
            foreach ($relations as $relation) {
                $this->with([$relation => function ($query) use ($limit) {
                    $query->limit($limit);
                }]);
            }
            return $this;
        });
        
        // Register ImageHelper as a global function for Blade and Inertia
        View::share('imageUrl', function($path, $default = '/images/default.png') {
            return ImageHelper::url($path, $default);
        });

        // Register a blade directive for images
        Blade::directive('image', function ($expression) {
            return "<?php echo App\\Helpers\\ImageHelper::url($expression); ?>";
        });
        
        // Set up HTTP cache headers for static assets
        if (!\Illuminate\Support\Facades\App::isLocal()) {
            $this->setupCacheHeaders();
        }
    }
    
    /**
     * Set up HTTP cache headers for better performance
     */
    private function setupCacheHeaders(): void
    {
        // Register a global middleware for cache headers
        app('router')->aliasMiddleware('cache.headers', \App\Http\Middleware\CacheHeaders::class);
        
        // Use the middleware on specific route groups
        \Illuminate\Support\Facades\Route::middleware('cache.headers')->prefix('storage')->group(function () {
            // This will automatically apply caching to all storage routes
        });
        
        // Also set up some basic DB query optimization
        \Illuminate\Support\Facades\DB::listen(function ($query) {
            // Log slow queries for optimization
            if ($query->time > 100) { // 100ms threshold
                \Illuminate\Support\Facades\Log::channel('daily')->info(
                    'Slow query: ' . $query->sql, 
                    ['bindings' => $query->bindings, 'time' => $query->time]
                );
            }
        });
    }
}
