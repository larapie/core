<?php

namespace Larapie\Core\Base;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\TestCase;
use Larapie\Core\Traits\ActAsUser;
use Larapie\Core\Traits\ArrayHasKeysAssert;
use Larapie\Core\Traits\CreatesApplication;

abstract class Test extends TestCase
{
    use ArraySubsetAsserts;
    use ArrayHasKeysAssert;
    use CreatesApplication;
    use ActAsUser;

    public function setUp(): void
    {
        parent::setUp();

        if (($user = $this->user()) !== null) {
            $this->actAs($user);
        }
    }
}
