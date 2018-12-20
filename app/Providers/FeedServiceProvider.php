<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Providers;


use App\Readers\FeedReader;
use Illuminate\Support\ServiceProvider;

/**
 * Class FeedServiceProvider
 *
 * @package App\Providers
 */
class FeedServiceProvider extends ServiceProvider
{
    const FEED_READER_HTTP_CLIENT = 'feed_reader_http_client';
    const FEED_READER             = 'feed_reader';
    /**
     * @var bool
     */
    protected $defer = true;

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
        $this->app->bind(FeedReader::class, function () {
            return new FeedReader();
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            self::FEED_READER
        ];
    }
}
