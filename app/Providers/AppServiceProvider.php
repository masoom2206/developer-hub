<?php

namespace App\Providers;

use App\Services\AdService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AdService::class);
    }

    public function boot(): void
    {
        // Share sponsored ads with the main layout
        View::composer('layouts.app', function ($view) {
            $adService = app(AdService::class);
            $view->with('headerAd', $adService->getActiveAd('header'));
            $view->with('sidebarAd', $adService->getActiveAd('sidebar'));
        });

        // @affiliate('hostinger') directive
        Blade::directive('affiliate', function ($expression) {
            return "<?php echo e(config('affiliates.' . trim($expression, \"'\\\"\") . '.url', '#')); ?>";
        });
        // Force Laravel to use native symlink() instead of exec()
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
