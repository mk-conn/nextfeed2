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
 * Class ArticleResourceTest
 *
 * @package Tests\Feature
 */
class ArticleResourceTest extends ApiRequest
{
    use ModelFactoryTrait, FeedReaderMock;
    
    const RESOURCE_TYPE = 'articles';
    
    /**
     *
     */
    public function testIndex()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        $this->createArticle($feed, [], 10);
        
        $response = $this->getJsonApi($this->apiUrl)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        
        $this->assertCount(10, $response['data']);
    }
    
    /**
     *
     */
    public function testRead()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        $article = $this->createArticle($feed);
        
        $this->getJsonApi($this->apiUrl . '/' . $article->id)
             ->assertStatus(Response::HTTP_OK)
             ->decodeResponseJson();
    }
    
    /**
     *
     */
    public function testUpdate()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        $article = $this->createArticle($feed);
        
        $update = [
            'data' => [
                'type'       => 'articles',
                'id'         => "{$article->id}",
                'attributes' => [
                    'read' => true,
                    'keep' => true
                ]
            ]
        ];
        
        $response = $this->patchJsonApi($this->apiUrl . '/' . $article->id, $update)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        $this->assertTrue(array_get($response, 'data.attributes.read'));
        $this->assertTrue(array_get($response, 'data.attributes.keep'));
    }
    
    /**
     *
     */
    public function testIndexOtherUsersFeedReturnsEmpty()
    {
        $this->mockFeedReader();
        $this->withUser();
        $otherUser = $this->createUser();
        $feed = $this->createFeed($this->user);
        $otherFeed = $this->createFeed($otherUser);
        $this->createArticle($otherFeed, [], 3);
        $this->createArticle($feed);
        
        $response = $this->getJsonApi($this->apiUrl . '/?filter[feed]=' . $otherFeed->id)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        $this->assertCount(0, $response['data']);
        
    }
    
    public function testUpdateWhitelistAttributes()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        $article = $this->createArticle($feed);
        
        $update = [
            'data' => [
                'type'       => 'articles',
                'id'         => "{$article->id}",
                'attributes' => [
                    'read'  => true,
                    'keep'  => true,
                    'title' => 'Ramalamadingdong'
                ]
            ]
        ];
        
        $response = $this->patchJsonApi($this->apiUrl . '/' . $article->id, $update)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        $this->assertTrue(array_get($response, 'data.attributes.read'));
        $this->assertTrue(array_get($response, 'data.attributes.keep'));
        $this->assertEquals($article->title, array_get($response, 'data.attributes.title'));
    }
    
    /**
     *
     */
    public function testArchivedArticles()
    {
        
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        
        $this->createArticle($feed, [], 4);
        $this->createArticle($feed, ['keep' => true], 6);
        
        // http://localhost:8500/api/v1/articles?fields%5Barticles%5D=title%2Cdescription%2Cauthor%2Ckeep%2Cread%2Curl%2Cupdated-date%2Ccategories&filter%5Bkeep%5D=true&page%5Bsize%5D=10&page%5Bnumber%5D=1&page%5Bsize%5D=25&sort=-updated-date%2C-id
        // filter[keep]=1&fields[article]=title,description,author,keep,read,url,updated-date,categories&page[size]=10&page[number]=1&sort[0]=-updated-date,-id
        $query_data = [
            'filter' => [
                'keep' => true,
            ],
            'fields' => [
                'article' => 'title,description,author,keep,read,url,updated-date,categories'
            ],
            'page'   => [
                'size'   => 10,
                'number' => 1
            ],
            'sort'   => '-updated-date,-id'
        
        ];
        
        $query = http_build_query($query_data);
        
        $response = $this->getJsonApi($this->apiUrl . '?' . $query)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        
        $this->assertCount(6, $response['data']);
    }
    
    /**
     *
     */
    public function testSearchArticles()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        
        $this->createArticle(
            $feed, [
            'title'   => 'Eine Artikel Überschrift',
            'content' => 'Some bloody content godamnit!'
        ]);
        $this->createArticle($feed, [], 10);
        
        $data = [
            'q' => 'Überschrift'
        ];
        $query = http_build_query($data);
        $response = $this->getJson($this->apiUrl . '/search?' . $query)
                         ->assertStatus(200)
                         ->decodeResponseJson();
        $this->assertCount(1, $response);

//        $q = 'bloody';
//        $response = $this->getJson($this->rootUrl . '/api/actions/articles/search/' . $q)
//                         ->assertStatus(200)
//                         ->decodeResponseJson();
//        $this->assertCount(1, array_get($response, 'data.attributes.result.articles'));
    }
    
    public function testMarkReadDoesNotCreateAudit()
    {
        $this->mockFeedReader();
        $this->withUser();
        $feed = $this->createFeed($this->user);
        $article = $this->createArticle(
            $feed, [
            'title'   => 'Eine Artikel Überschrift',
            'content' => 'Some bloody content godamnit!',
            'read'    => false
        ]);
        
        $this->patchJsonApi(
            $this->apiUrl . '/' . $article->id, [
            'data' => [
                'type'       => "articles",
                'id'         => "{$article->id}",
                'attributes' => [
                    'read' => true
                ]
            ]
        ]);
        
        $article = Article::find($article->id);
        $audits = $article->audits;
        $this->assertCount(0, $audits);
        
    }
    
    
}
