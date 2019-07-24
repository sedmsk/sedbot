<?php

namespace App\Providers;

use App\Facades\Telegram as TelegramFacade;
use App\Helpers\Telegram;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider implements DeferrableProvider
{
    protected $commands = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TelegramFacade::class, function ($app) {
            return new Telegram;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [TelegramFacade::class];
    }
}
