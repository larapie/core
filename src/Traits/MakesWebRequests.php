<?php

namespace Larapie\Core\Traits;

use Larapie\Core\Responses\TestResponse;

trait MakesWebRequests
{
    protected function http(string $method, string $route, array $payload = [], array $headers = []): TestResponse
    {
        if (!in_array($method, ['GET', 'POST', 'PATCH', 'DELETE', 'PUT'])) {
            throw new \Exception('Invalid Http Method');
        }

        return new TestResponse($this->json($method, env('APP_URL').$route, $payload, $headers)->baseResponse);
    }
}
