<?php

namespace Mazimez\Gigapay;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Mazimez\Gigapay\Console\Commands\WebhookCommand;

class GigapayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/gigapay.php',
            'gigapay'
        );
        $this->publishes([
            __DIR__ . '/../config/gigapay.php' => config_path('gigapay.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                WebhookCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}