<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        // Map prefix 'form' ke folder baru anonymous components: resources/views/components/ui/form
        Blade::anonymousComponentPath(resource_path('views/components/ui/form'), 'form');
    }
}
