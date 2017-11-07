<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests\Feature;


use App\Models\Article;
use Illuminate\Http\Response;
use Tests\TestResource;
use Tests\Traits\FeedReaderMock;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class FeedResourceTest
 *
 * @package Tests\Feature
 */
class FeedResourceTest extends TestResource
{
    use ModelFactoryTrait, FeedReaderMock;

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

        $this->url = $this->apiUrl . '/feeds';
    }

    /**
     * Test creation of a feed
     */
    public function testCreate()
    {

        $this->mockFeedReader();

        $folder = $this->createFolder();

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
                    ]
                ]
            ]
        ];

        $response = $this->postJson('api/v1/feeds', $create)
                         ->assertStatus(Response::HTTP_CREATED)
                         ->decodeResponseJson();

        $this->assertEquals('Feed Title', array_get($response, 'data.attributes.name'));
        $this->assertEquals($this->user->id, array_get($response, 'data.relationships.user.data.id'));
    }

    /**
     * Test create is forbidden for different users
     */
    public function testCreateForbidden()
    {
        $this->mockFeedReader(false);

        $user = $this->createUser();
        $folder = $this->createFolder();
        $differentUser = $this->createUser();

        $this->be($differentUser);

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

        $this->postJson('api/v1/feeds', $create)
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     */
    public function testIndex()
    {
        $mock_function = [$this, 'mockFeedReader'];
        $this->createFeed(null, null, [], 5, $mock_function);

        $response = $this->getJson('api/v1/feeds')
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(5, $response['data']);
    }


    /**
     *
     */
    public function testSaveFeedSettings()
    {
        $this->mockFeedReader();
        $feed = $this->createFeed();

        $settings = [
            'articles' => [
                'keep' => 20
            ]
        ];

        $update = [
            'data' => [
                'type'       => 'feeds',
                'id'         => "$feed->id",
                'attributes' => [
                    'settings' => $settings
                ]
            ]
        ];

        $response = $this->patchJson($this->url . '/' . $feed->id, $update)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertEquals(20, array_get($response, 'data.attributes.settings.articles.keep'));
    }

    /**
     *
     */
    public function testMarkAllRead()
    {
        $this->mockFeedReader();
        $feed = $this->createFeed();
        $articles = $this->createArticle($feed, [], 10);
        $newerArticles = $this->createArticle($feed, [], 2);
        $lastId = $articles->max('id');
        $query = [
            'action' => 'read',
            'params' => [
                'feed_id'         => $feed->id,
                'last_article_id' => $lastId
            ]
        ];
        $q = http_build_query($query);

        $this->getJson($this->apiUrl . '/feed-actions?' . $q)
             ->assertSuccessful()
             ->decodeResponseJson();

        $articles = Article::whereFeedId($feed->id)
                           ->where('read', false)
                           ->get();

        $this->assertEquals($newerArticles->count(), $articles->count());
    }


}
