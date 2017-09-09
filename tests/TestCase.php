<?php

namespace Tests;


use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
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
        $content = $data ? json_encode($data) : null;

        $headers = [
            'CONTENT-TYPE'     => MediaTypeInterface::JSON_API_MEDIA_TYPE,
            'ACCEPT'           => MediaTypeInterface::JSON_API_MEDIA_TYPE,
            'CONTENT_LENGTH'   => mb_strlen($content, '8bit'),
            'X-Requested-With' => 'XMLHttpRequest',
            'X-CSRF-TOKEN'     => csrf_token(),
        ];

        return parent::json($method, $uri, $data, $headers);
    }
}
