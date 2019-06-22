<?php

namespace Larapie\Core\Base;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class QueuedListener implements ShouldQueue
{
    use InteractsWithQueue;
}
