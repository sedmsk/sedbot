<?php

namespace App\Providers;

use App\Helpers\Phrase;
use Illuminate\Support\ServiceProvider;

class PhraseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('phrase', function ($app) {
            return new Phrase();
        });
    }
}
