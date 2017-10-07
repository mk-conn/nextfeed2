<?php

namespace App\Providers;


use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // we don't have the idehelper stuff in production package (and we dont want it there)
        if ($this->app->environment() === 'local' && class_exists(IdeHelperServiceProvider::class)) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
