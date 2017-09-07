<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use PicoFeed\Reader\Reader;

/**
 * Class PicoFeedServiceProvider
 *
 * @package App\Providers
 */
class FeedServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {

    }

    /**
     *
     */
    public function register()
    {
        $reader = new Reader();
        $this->app->instance(Reader::class, $reader);
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            Reader::class
        ];
    }
}
