<?php

namespace Tests\Feature;


use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HasOrderTest extends TestCase
{

    use DatabaseMigrations;

    protected function setUp()
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


}
