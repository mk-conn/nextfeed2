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
use Tests\TestResource;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class FolderResourceTest
 *
 * @package Tests\Feature
 */
class FolderResourceTest extends TestResource
{
    use DatabaseMigrations, ModelFactoryTrait;

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

        $response = $this->getJson('api/v1/folders')
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();
        $this->assertCount(10, $response['data']);
    }

    /**
     *
     */
    public function testIndexFilterByUser()
    {
        $this->createFolder($this->user, [], 5);

        $response = $this->getJson('api/v1/folders')
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(5, $response['data']);
    }

    /**
     *
     */
    public function testIndexWithFilterForbiddenForUser()
    {
        $user = $this->createUser(['username' => 'anthony']);
        $this->createFolder($user, [], 5);

        $this->getJson('api/v1/folders?filter[user]=anthony')
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     */
    public function testRead()
    {
        $folder = $this->createFolder(null, ['name' => 'News']);

        $response = $this->getJson('api/v1/folders/' . $folder->id)
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertEquals('News', array_get($response, 'data.attributes.name'));
    }

}
