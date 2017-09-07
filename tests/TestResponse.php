<?php

namespace Tests;


use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * Class TestResponse
 *
 * @package Tests
 */
class TestResponse extends \Illuminate\Foundation\Testing\TestResponse
{

    /**
     * @param int $status
     *
     * @return $this
     */
    public function assertStatus($status)
    {
        $actual = $this->getStatusCode();

        $jsonResponse = null;
        if ($status !== Response::HTTP_NO_CONTENT) {
            $jsonResponse = json_encode($this->decodeResponseJson(), JSON_PRETTY_PRINT);
        }

        PHPUnit::assertTrue(
            $actual === $status,
            "Expected status code {$status} but received {$actual}." . PHP_EOL . PHP_EOL .
            $jsonResponse
        );

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function assertJsonFragment(array $data)
    {

        $actual = json_encode(Arr::sortRecursive(
            (array)$this->decodeResponseJson()
        ));

        foreach (Arr::sortRecursive($data) as $key => $value) {
            $expected = substr(json_encode([$key => $value]), 1, -1);

            $actualMsg = json_encode(json_decode($actual), JSON_PRETTY_PRINT);

            PHPUnit::assertTrue(
                Str::contains($actual, $expected),
                'Unable to find JSON fragment: ' . PHP_EOL . PHP_EOL .
                "[{$expected}]" . PHP_EOL . PHP_EOL .
                'within' . PHP_EOL . PHP_EOL .
                "[{$actualMsg}]."
            );
        }

        return $this;
    }


}
