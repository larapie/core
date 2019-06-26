<?php

namespace Larapie\Core\Base;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\TestCase;
use Larapie\Core\Traits\ArrayHasKeysAssert;
use Larapie\Core\Traits\CreatesApplication;

abstract class Test extends TestCase
{
    use ArraySubsetAsserts, ArrayHasKeysAssert, CreatesApplication;
}
