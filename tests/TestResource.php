<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;


/**
 * Class TestResource
 *
 * @package Tests
 */
class TestResource extends TestCase
{
    use RefreshDatabase;


    public $apiUrl;

    public $user;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $namespace = config('json-api-v1.url.namespace');
        $this->apiUrl = 'http://localhost' . $namespace;

       

    }

    /**
     *
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Test is not implemented.');
    }

    /**
     *
     */
    public function testRead()
    {
        $this->markTestIncomplete('Test is not implemented.');
    }

    /**
     *
     */
    public function testCreate()
    {
        $this->markTestIncomplete('Test is not implemented.');
    }

    /**
     *
     */
    public function testUpdate()
    {
        $this->markTestIncomplete('Test is not implemented.');
    }


}
