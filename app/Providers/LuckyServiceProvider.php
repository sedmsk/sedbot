<?php

namespace App\Providers;

use App\Helpers\Lucky;
use Illuminate\Support\ServiceProvider;

class LuckyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lucky', function ($app) {
            return new Lucky();
        });
    }
}
