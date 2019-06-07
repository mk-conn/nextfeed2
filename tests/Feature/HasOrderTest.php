<?php

namespace Tests\Feature;


use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class HasOrderTest
 *
 * @package Tests\Feature
 */
class HasOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     */
    public function testFolderHasOrder()
    {

        $folder = new Folder();
        $folder->name = "folder_a";
        $folder->save();

        $this->assertEquals(1, $folder->order);
    }

    /**
     *
     */
    protected function setUp()
    : void
    {
        parent::setUp();

        $user = new User([
            'username' => 'unittest',
            'fullname' => 'Unit Test',
            'password' => 'some randomn string',
            'email'    => 'unittest@localhost',
        ]);
        $user->save();
        $this->be($user);
    }


}
