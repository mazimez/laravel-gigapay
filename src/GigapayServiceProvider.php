<?php

namespace Mazimez\Gigapay;

use Illuminate\Support\ServiceProvider;

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
