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

    /**
     *
     */
    public function testIndex()
    {
        $user = $this->createUser();
        $this->createFolder($user, [], 5);

        $response = $this->getJson('api/v1/folders')
                         ->assertStatus(Response::HTTP_OK)
                         ->decodeResponseJson();

        $this->assertCount(5, $response['data']);
    }

    /**
     *
     */
    public function testRead()
    {

    }

}
