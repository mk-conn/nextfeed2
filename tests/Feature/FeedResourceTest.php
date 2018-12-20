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
use Tests\ApiRequest;
use Tests\Traits\FeedReaderMock;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class FeedResourceTest
 *
 * @package Tests\Feature
 */
class FeedResourceTest extends ApiRequest
{
    use ModelFactoryTrait, FeedReaderMock;

    const RESOURCE_TYPE = 'feeds';
    /**
     * @var
     */
    protected $feedReaderMock;

    /**
     * Test creation of a feed
     */
    public function testCreate()
    {

        $this->mockFeedReader();
        $this->withUser();
        $folder = $this->createFolder($this->user);

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

        $response = $this->postJsonApi($this->apiUrl, $create)
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
        $this->mockFeedReader();

        $user = $this->createUser();
        $folder = $this->createFolder($user);
        $differentUser = $this->createUser();
        $this->withUser($differentUser);

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

        $this->postJsonApi($this->apiUrl, $create)
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     */
    public function testIndex()
    {
        $this->mockFeedReader();
        $this->withUser();
        $this->createFeed($this->user, null, [], 5);

        $response = $this->getJsonApi($this->apiUrl)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(5, $response[ 'data' ]);
    }


    /**
     *
     */
    public function testSaveFeedSettings()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);

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

        $response = $this->patchJsonApi($this->apiUrl . '/' . $feed->id, $update)
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
        $this->withUser();
        $feed = $this->createFeed($this->user);
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

        $this->getJsonApi($this->apiUrl . '/feed-actions?' . $q)
             ->assertSuccessful()
             ->decodeResponseJson();

        $articles = Article::whereFeedId($feed->id)
                           ->where('read', false)
                           ->get();

        $this->assertEquals($newerArticles->count(), $articles->count());
    }
}
