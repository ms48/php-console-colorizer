<?php

namespace Ms48\PhpConsoleColorizer\Laravel;

use Illuminate\Support\ServiceProvider;

class ConsoleColorizerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {        
        $this->app->bind('consoleColorizer', function () {
            return new \Ms48\PhpConsoleColorizer\ConsoleColorizer();
        });
    }
}
