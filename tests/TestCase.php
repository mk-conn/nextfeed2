<?php

namespace Tests;


use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\TestHttpRequest;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TestHttpRequest;


    /**
     * @param string $method
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers = [
            'CONTENT-TYPE'     => 'application/vnd.api+json',
            'ACCEPT'           => 'application/vnd.api+json',
            'X-Requested-With' => 'XMLHttpRequest',
            'X-CSRF-TOKEN'     => csrf_token(),
        ];

        return parent::json($method, $uri, $data, $headers);
    }
}
