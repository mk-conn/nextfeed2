<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Providers;


use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Zend\Feed\Reader\Reader;

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
        $this->app->singleton(self::FEED_READER, function (Application $app, $params = []) {
            $params = array_merge(['uri' => null, 'etag' => null, 'last_modified' => null], $params);

            if ($params[ 'last_modified' ] instanceof Carbon) {
                $params[ 'last_modified' ] = $params[ 'last_modified ' ]->format(Carbon::ISO8601);
            }
            $httpClient = Reader::getHttpClient();
            $app->instance(self::FEED_READER_HTTP_CLIENT, $httpClient);

            return Reader::import($params[ 'uri' ], $params[ 'etag' ], $params[ 'last_modified' ]);
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
