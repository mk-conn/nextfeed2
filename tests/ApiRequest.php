<?php


namespace Tests;


use CloudCreativity\LaravelJsonApi\Testing\MakesJsonApiRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ApiRequest
 *
 * @package Tests
 */
class ApiRequest extends TestCase
{
    use RefreshDatabase, MakesJsonApiRequests;

    /**
     *
     */
    const RESOURCE_TYPE = null;


    /**
     * @var
     */
    public $apiUrl;

    public $rootUrl = 'http://localhost';

    /**
     * @var string
     */
    public $baseUrl = '';

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $namespace = config('json-api-v1.url.namespace');
        $this->baseUrl = $this->rootUrl . $namespace;
        $this->apiUrl = $this->baseUrl;

        if (defined('static::RESOURCE_TYPE')) {
            $this->apiUrl = $this->baseUrl . '/' . constant('static::RESOURCE_TYPE');
        }
    }

    /**
     * Test read single resource
     */
    public function testRead()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     * Test read single resource with included relations
     * (compound documents)
     */
    public function testReadWithIncludes()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     * Test get all resource objects
     */
    public function testIndex()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     * Test get all resource objects with included relations
     */
    public function testIndexWithIncludes()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     * Test create resource
     */
    public function testCreate()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     * Test delete resource
     */
    public function testDelete()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     * Test update resource
     */
    public function testUpdate()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     *
     */
    public function testValidationFailsOnCreate()
    {
        $this->markTestSkipped('This test is not implemented');
    }

    /**
     *
     */
    public function testValidationFailsOnUpdate()
    {
        $this->markTestSkipped('This test is not implemented');
    }
}