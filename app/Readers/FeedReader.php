<?php


namespace App\Readers;


use App\Models\Feed;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Masterminds\HTML5;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\Http\ZendHttpClientDecorator;
use Zend\Feed\Reader\Reader;
use Zend\Http\Client;
use Zend\Http\Request;


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
    
    /**
     * @param string $url
     * @param array  $options
     *
     * @return string
     */
    public static function parseUrl(
        string $url,
        $options = []
    ) {
        
        $options = array_merge(
            [
                'scheme'   => true,
                'host'     => true,
                'path'     => true,
                'query'    => false,
                'fragment' => false
            ],
            $options);
        
        $parsed = parse_url($url);
        $result = '';
        
        if ($options['scheme'] && isset($parsed['scheme'])) {
            $result .= $parsed['scheme'] . '://';
        }
        if ($options['host'] && isset($parsed['host'])) {
            $result .= $parsed['host'];
        }
        if ($options['path'] && isset($parsed['path'])) {
            $result .= $parsed['path'];
        }
        if ($options['query'] && isset($parsed['query'])) {
            $result .= $parsed['query'];
        }
        if ($options['fragment'] && isset($parsed['fragment'])) {
            $result .= $parsed['fragment'];
        }
        
        return $result;
    }
    
    public static function fetchIcon(Feed $feed)
    {
        $feedIcon = null;
        
        $retrieveImage = function ($imageUrl) {
            $size = @getimagesize($imageUrl);
            
            return isset($size['mime']);
        };
        
        // fallbacks
        $icon = self::parseUrl(
                $feed->site_url,
                ['scheme' => true, 'host' => true, 'path' => false]
            ) . '/favicon.ico';
        // first shot is the obvious one
        if ($retrieveImage($icon)) {
            $feedIcon = $icon;
            
            return $feedIcon;
        }
        
        try {
            $request = new Request();
            $request->setUri($feed->site_url);
            $request->setMethod('GET');
            $client = new Client();
            $response = $client->send($request);
            $body = trim($response->getBody());
            $body = (new \tidy)->repairString(
                $body,
                [
                    'drop-proprietary-attributes' => true,
                    'fix-uri'                     => true,
                    'wrap'                        => false,
                    //                'input-xml'   => false,
                    'output-xml'                  => true,
                    'quote-marks'                 => true
                ]
            );
            
            if (!empty($body)) {
                libxml_use_internal_errors(true);
                // now lets check, what the site provides
                $icons[] = 'favicon.ico'; // last shot if its under the site-url
                $html5 = new HTML5(['encode_entities' => true]);
                $doc = $html5->loadHTML($body);
                // xpath did not work for some feeds (probably the html was to fucked up :-/
                $xml = simplexml_import_dom($doc);
                $errors = libxml_get_errors();
                libxml_clear_errors();
                
                if (empty($errors)) {
                    foreach ($xml->head->link as $link) {
                        $rel = $link->attributes()->rel;
                        $rel = strtolower($rel);
                        if ($rel === 'icon' || $rel === 'shortcut icon' || $rel === 'icon shortcut') {
                            $icons = array_prepend($icons, (string)$link->attributes()->href);
                        }
                    }
                }
                
                foreach ($icons as $icon) {
                    $icon = trim($icon);
                    // if is relative path, just add the site_url, leave as is otherwise
                    if (starts_with($icon, 'http')) {
                        $imageUrl = $icon;
                    } else {
                        $imageUrl = rtrim($feed->site_url, '/') . '/' . ltrim($icon, '/');
                    }
                    
                    if (strpos($imageUrl, '?')) {
                        $imageUrl = explode('?', $imageUrl)[0];
                    }
                    // check if item is available and not a 404
                    if ($retrieveImage($imageUrl)) {
                        $feedIcon = $imageUrl;
                        break;
                    }
                }
                
                return $feedIcon;
            }
        } catch (\Exception $e) {
            return null;
        }
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
            $headers['If-None-Match'] = [$params[ self::ETAG ]];
        }
        if ($params[ self::LAST_MODIFIED ]) {
            $headers['If-Modified-Since'] = [$params[ self::LAST_MODIFIED ]];
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