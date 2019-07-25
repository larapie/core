<?php

namespace Larapie\Core\Traits;

use Larapie\Core\Responses\TestResponse;
use Larapie\Core\Support\Facades\Larapie;

trait MakesApiRequests
{
    protected function http(string $method, string $route, array $payload = [], array $headers = []): TestResponse
    {
        if (!in_array($method, ['GET', 'POST', 'PATCH', 'DELETE', 'PUT'])) {
            throw new \Exception('Invalid Http Method');
        }

        return new TestResponse($this->json($method, Larapie::getApiUrl().$route, $payload, $headers)->baseResponse);
    }
}
