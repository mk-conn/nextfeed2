<?php
/**
 * Created by PhpStorm.
 * User: mkruege
 * Date: 12.10.17
 * Time: 14:36
 */

namespace Tests\Traits;


use App\Providers\FeedServiceProvider;
use Zend\Feed\Reader\Reader;
use Zend\Http\Client;

/**
 * Trait FeedReaderMock
 *
 * @package Tests\Traits
 */
trait FeedReaderMock
{

    /**
     * @var
     */
    protected $feedReaderMock;
    protected $httpClientMock;

    public function createApplication()
    {
        $app = parent::createApplication();

        $this->feedReaderMock = \Mockery::mock(Reader::class);
        $this->httpClientMock = \Mockery::mock(Client::class);

        $app->instance(FeedServiceProvider::FEED_READER, $this->feedReaderMock);
        $app->instance(FeedServiceProvider::FEED_READER_HTTP_CLIENT, $this->httpClientMock);

        return $app;
    }

    /**
     *
     */
    public function setup()
    {
        parent::setup();
    }


    /**
     * @param bool $discover
     */
    protected function mockFeedReader(bool $discover = true)
    {

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('get')
                   ->andReturn('<xml></xml>');

        $this->feedReaderMock->shouldReceive('import')
                             ->andReturn();
//        $clientMock->shouldReceive('getEncoding')
//                   ->andReturn('utf8');
//        $clientMock->shouldReceive('getEtag')
//                   ->andReturn('somerubbishgoesoutfromhere');

//        $parserMock = \Mockery::mock(Parser::class);
//        $parsedFeedMock = \Mockery::mock(Feed::class);


//        if ($discover) {
//            $this->feedReaderMock->shouldReceive('discover')
//                                 ->once()
//                                 ->with(\Mockery::any())
//                                 ->andReturn($clientMock);
////            $this->feedReaderMock->shouldReceive('getParser')
////                                 ->once()
////                                 ->andReturn($parserMock);
//
//
////            $parserMock->shouldReceive('execute')
////                       ->andReturn($parsedFeedMock);
//
//            $parsedFeedMock->shouldReceive('getId')
//                           ->andReturn(str_random(52));
//            $parsedFeedMock->shouldReceive('getTitle')
//                           ->andReturn('Feed Title');
//            $parsedFeedMock->shouldReceive('getDescription')
//                           ->andReturn('Feed Description');
//            $parsedFeedMock->shouldReceive('getSiteUrl')
//                           ->andReturn('http://example.com');
//            $parsedFeedMock->shouldReceive('getFeedUrl')
//                           ->andReturn('http://example.com/feed.rss');
//            $parsedFeedMock->shouldReceive('getLanguage')
//                           ->andReturn('en');
//            $parsedFeedMock->shouldReceive('getLogo')
//                           ->andReturn('http://example.com/logo.png');
//            $parsedFeedMock->shouldReceive('getIcon')
//                           ->andReturn('http://example.com/favicon.ico');
//            $parsedFeedMock->shouldReceive('getItems')
//                           ->andReturn([]);
//        }
    }
}
