<?php

namespace Nsaliu\Uri;

use Illuminate\Support\ServiceProvider;

class UriServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\Nsaliu\Uri\Uri::class, function () {
            return new \Nsaliu\Uri\Uri();
        });

        $this->app->alias(\Nsaliu\Uri\Uri::class, 'URIHelper');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
