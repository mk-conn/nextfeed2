<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Providers;


use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Zend\Feed\Reader\Reader;

/**
 * Class FeedServiceProvider
 *
 * @package App\Providers
 */
class FeedServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('FeedReader', function ($app, $params = []) {
            $params = array_merge([
                'uri'          => null,
                'etag'         => null,
                'lastModified' => null

            ], $params);
            list($uri, $etag, $lastModified) = $params;
            if ($lastModified instanceof Carbon) {
                $lastModified = $lastModified->format(Carbon::ISO8601);
            }
            $httpClient = Reader::getHttpClient();
            $app->instance('FeedHttpClient', $httpClient);

            return Reader::import($uri, $etag, $lastModified);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            'Feed'
        ];
    }
}
