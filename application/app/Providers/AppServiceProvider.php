<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        //[growcrm] disable debug bar in production mode
        if (!env('APP_DEBUG_TOOLBAR')) {
            \Debugbar::disable();
        }

        //[growcrm]
        $this->app->bind(Carbon::class, function (Container $container) {
            return new Carbon('now', 'Europe/Brussels');
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
