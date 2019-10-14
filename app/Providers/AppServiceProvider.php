<?php

namespace App\Providers;


use App\ViewComposers\AppComposer;
use Illuminate\Support\ServiceProvider;
use CloudCreativity\LaravelJsonApi\LaravelJsonApi;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LaravelJsonApi::defaultApi('v1');
        View::composer('app', AppComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // we don't have the idehelper stuff in production package (and we dont want it there)
        if ($this->app->environment() === 'local' && class_exists
            (\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class)) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
