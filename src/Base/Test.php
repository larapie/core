<?php

namespace Larapie\Core\Base;

use Illuminate\Foundation\Testing\TestCase;
use Larapie\Core\Traits\CreatesApplication;

abstract class Test extends TestCase
{
    use CreatesApplication;
}
