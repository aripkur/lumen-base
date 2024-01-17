<?php

namespace App\Providers;

use App\Services\Captcha;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Captcha::class, function ($app) {
            return new Captcha($app['config']['captcha']);
        });
    }
}
