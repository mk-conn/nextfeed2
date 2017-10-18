<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestResource;
use Tests\Traits\FeedReaderMock;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class ArticleResourceTest
 *
 * @package Tests\Feature
 */
class ArticleResourceTest extends TestResource
{
    use RefreshDatabase, ModelFactoryTrait, FeedReaderMock;

    /**
     *
     */
    public function setup()
    {
        parent::setUp();
    }

    /**
     *
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not yet implemented');
    }

    /**
     *
     */
    public function testRead()
    {
        $this->markTestIncomplete('Not yet implemented');
    }

    /**
     *
     */
    public function testArchivedArticles()
    {

        $this->mockFeedReader();
        $feed = $this->createFeed();

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

        $response = $this->getJson('api/v1/articles?' . $query)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(6, $response['data']);
    }

    public function testSearchArticles()
    {
        $this->mockFeedReader();
        $feed = $this->createFeed();

        $this->createArticle($feed, ['title' => 'Eine Artikel Überschrift']);
        $this->createArticle($feed, [], 10);

        $query = ['action' => 'search', 'params' => ['q' => 'Überschrift']];
        $q = http_build_query($query);

        $response = $this->getJson('api/v1/article-actions?' . $q)
                         ->assertStatus(200)
                         ->decodeResponseJson();

        $this->assertCount(1, array_get($response, 'data.attributes.result.articles'));
    }


}
