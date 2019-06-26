<?php

namespace Larapie\Core\Traits;

trait ArrayHasKeysAssert
{
    public function assertArrayHasKeys(array $keys, $data)
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }
}
