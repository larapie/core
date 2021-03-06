<?php

namespace Larapie\Core\Responses;

use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse as ParentTestResponse;

class TestResponse extends ParentTestResponse
{
    public function assertStatus($status)
    {
        $actual = $this->getStatusCode();
        PHPUnit::assertTrue(
            $actual === $status,
            "Expected status code {$status} but received {$actual}."."\n".$this->getContent()
        );

        return $this;
    }

    public function decode()
    {
        return json_decode($this->getContent(), true)['data'] ?? json_decode($this->getContent(), true);
    }
}
