<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests\Feature;


use Illuminate\Http\Response;
use Tests\ApiRequest;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class FolderResourceTest
 *
 * @package Tests\Feature
 */
class FolderResourceTest extends ApiRequest
{
    use  ModelFactoryTrait;

    const RESOURCE_TYPE = 'folders';

    /**
     *
     */
    public function setUp()
    {
        parent::setup();
    }

    /**
     *
     */
    public function testIndex()
    {
        $this->createFolder(null, [], 10);

        $response = $this->getJsonApi($this->apiUrl)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        $this->assertCount(10, $response[ 'data' ]);
    }

    /**
     *
     */
    public function testIndexFilterByUser()
    {
        $this->createFolder($this->user, [], 5);

        $response = $this->getJsonApi($this->apiUrl)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(5, $response[ 'data' ]);
    }

    /**
     *
     */
    public function testIndexWithFilterForbiddenForUser()
    {
        $user = $this->createUser(['username' => 'anthony']);
        $this->createFolder($user, [], 5);

        $this->getJsonApi($this->apiUrl . '?filter[user]=anthony')
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     */
    public function testRead()
    {
        $folder = $this->createFolder(null, ['name' => 'News']);

        $response = $this->getJsonApi($this->apiUrl . $folder->id)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertEquals('News', array_get($response, 'data.attributes.name'));
    }

}
