<?php

namespace App\Providers;

use App\Models\Pli;
use App\Observers\PliObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
            $this->app->bind('path.public', function()
        {
            return base_path('public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //

        // Pli::observe(PliObserver::class);
    }

//     public function boot()
// {
//     Pli::observe(PliObserver::class);
// }
}
