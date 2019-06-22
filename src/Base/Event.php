<?php

namespace Larapie\Core\Base;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels, Dispatchable;
}
