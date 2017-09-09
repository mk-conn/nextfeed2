<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use PicoFeed\Client\Client;
use PicoFeed\Parser\Feed;
use PicoFeed\Parser\Parser;
use PicoFeed\Reader\Reader;
use Tests\TestResource;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class FeedResourceTest
 *
 * @package Tests\Feature
 */
class FeedResourceTest extends TestResource
{
    use DatabaseMigrations, ModelFactoryTrait;

    /**
     * @var
     */
    protected $feedReaderMock;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->feedReaderMock = \Mockery::mock(Reader::class);

        $this->app->instance(Reader::class, $this->feedReaderMock);
    }

    /**
     * Mock everything feed fetching/parsing related
     */
    protected function mockIt()
    {
        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('getUrl')
                   ->andReturn('http://example.com/feed.rss');
        $clientMock->shouldReceive('getContent')
                   ->andReturn('<xml></xml>');
        $clientMock->shouldReceive('getEncoding')
                   ->andReturn('utf8');
        $clientMock->shouldReceive('getEtag')
                   ->andReturn('somerubbishgoesoutfromhere');

        $parserMock = \Mockery::mock(Parser::class);
        $parsedFeedMock = \Mockery::mock(Feed::class);

        $this->feedReaderMock->shouldReceive('discover')
                             ->once()
                             ->with(\Mockery::any())
                             ->andReturn($clientMock);
        $this->feedReaderMock->shouldReceive('getParser')
                             ->once()
                             ->andReturn($parserMock);


        $parserMock->shouldReceive('execute')
                   ->andReturn($parsedFeedMock);

        $parsedFeedMock->shouldReceive('getId')
                       ->andReturn(str_random(52));
        $parsedFeedMock->shouldReceive('getTitle')
                       ->andReturn('Feed Title');
        $parsedFeedMock->shouldReceive('getDescription')
                       ->andReturn('Feed Description');
        $parsedFeedMock->shouldReceive('getSiteUrl')
                       ->andReturn('http://example.com');
        $parsedFeedMock->shouldReceive('getFeedUrl')
                       ->andReturn('http://example.com/feed.rss');
        $parsedFeedMock->shouldReceive('getLanguage')
                       ->andReturn('en');
        $parsedFeedMock->shouldReceive('getLogo')
                       ->andReturn('http://example.com/logo.png');
        $parsedFeedMock->shouldReceive('icon')
                       ->andReturn('http://example.com/favicon.ico');
        $parsedFeedMock->shouldReceive('getItems')
                       ->andReturn([]);
    }


    /**
     * Test creation of a feed
     */
    public function testCreate()
    {

        $this->mockIt();

        $folder = $this->createFolder();
        $user = $folder->user;

        $create = [
            'data' => [
                'type'          => 'feeds',
                'attributes'    => [
                    'url' => 'golem.de'
                ],
                'relationships' => [
                    'folder' => [
                        'data' => [
                            'type' => 'folders',
                            'id'   => "$folder->id"
                        ]
                    ],
                    'user'   => [
                        'data' => [
                            'type' => 'users',
                            'id'   => "$user->id"
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJson('api/v1/feeds', $create)
                         ->assertStatus(Response::HTTP_CREATED)
                         ->decodeResponseJson();

        $this->assertEquals('Feed Title', array_get($response, 'data.attributes.name'));
    }

    /**
     *
     */
    public function testIndex()
    {
        $mock_function = [$this, 'mockIt'];
        $this->createFeed(null, null, [], 5, $mock_function);

        $response = $this->getJson('api/v1/feeds')
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(5, $response['data']);

    }


}
