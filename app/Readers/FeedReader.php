<?php


namespace App\Readers;


use Carbon\Carbon;
use Illuminate\Http\Response;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\Http\ZendHttpClientDecorator;
use Zend\Feed\Reader\Reader;
use Zend\Http\Client;

/**
 *
 */
class FeedReader
{
    const ETAG          = 'etag';
    const LAST_MODIFIED = 'last_modified';
    const URI           = 'uri';
    /**
     * @var ZendHttpClientDecorator
     */
    protected $httpClient;

    protected $meta = [];

    public static function parseUrl(string $url, $options = ['query' => false])
    {
        $parsed = parse_url($url);
        $result = $parsed[ 'scheme' ] . '://' . $parsed[ 'host' ] . $parsed[ 'path' ];

        if ($options[ 'query' ]) {
            $result .= $parsed[ 'query' ];
        }

        return $result;
    }

    /**
     * Self implemented since zend_feed's reader only works with etag and last modified when zenc_cache is enabled,
     * which is to much of requirments here
     *
     * @param array $params
     *
     * @return FeedInterface
     */
    public function read($params = [])
    {
        $params = array_merge(
            [
                self::URI           => null,
                self::ETAG          => null,
                self::LAST_MODIFIED => null
            ],
            $params
        );

        if ($params[ self::LAST_MODIFIED ] instanceof Carbon) {
            $params[ self::LAST_MODIFIED ] = $params[ self::LAST_MODIFIED ]->format(Carbon::ISO8601);
        }
        $this->httpClient = Reader::getHttpClient();

        $headers = [];
        if ($params[ self::ETAG ]) {
            $headers[ 'If-None-Match' ] = [$params[ self::ETAG ]];
        }
        if ($params[ self::LAST_MODIFIED ]) {
            $headers[ 'If-Modified-Since' ] = [$params[ self::LAST_MODIFIED ]];
        }

        $response = $this->httpClient->get($params[ self::URI ], $headers);
        $etag = $response->getHeaderLine('ETag');
        $lastModified = $response->getHeaderLine('last-modified');
        $this->meta[ $params[ self::URI ] ][ self::LAST_MODIFIED ] = $lastModified;
        $this->meta[ $params[ self::URI ] ][ self::ETAG ] = $etag;
        if ($response->getStatusCode() !== Response::HTTP_NOT_MODIFIED) {
            if ($body = $response->getBody()) {

                $reader = Reader::importString($body);
                $reader->setOriginalSourceUri($params[ self::URI ]);

                return $reader;
            }
        }

        return null;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient->getDecoratedClient();
    }

    /**
     * @param $uri
     *
     * @return mixed
     */
    public function getLastModified($uri)
    {
        if (isset($this->meta[ $uri ][ self::LAST_MODIFIED ])) {
            return $this->meta[ $uri ][ self::LAST_MODIFIED ];
        }
    }

    /**
     * @param $uri
     *
     * @return mixed
     */
    public function getEtag($uri)
    {
        if (isset($this->meta[ $uri ][ self::ETAG ])) {
            return $this->meta[ $uri ][ self::ETAG ];
        }
    }

    public function discover($url)
    {
        $this->httpClient = Reader::getHttpClient();

        return Reader::findFeedLinks($url);
    }

}