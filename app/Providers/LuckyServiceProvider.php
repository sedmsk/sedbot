<?php

namespace App\Providers;

use App\Facades\Lucky as LuckyFacade;
use App\Helpers\Lucky;
use Illuminate\Support\ServiceProvider;

class LuckyServiceProvider extends ServiceProvider
{
    /** @var bool  */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LuckyFacade::class, function ($app) {
            return new Lucky();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return array
     */
    public function provides(): array
    {
        return [LuckyFacade::class];
    }
}
