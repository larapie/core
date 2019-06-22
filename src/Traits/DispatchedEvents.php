<?php

namespace Larapie\Core\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

trait DispatchedEvents
{
    protected function listenForEvents()
    {
        Event::fake();
    }

    protected function getDispatchedEvents(?string $class): Collection
    {
        $events = new Collection([]);
        Event::assertDispatched($class, function ($event) use (&$events) {
            $events->put(get_class($event), $event);
            return true;
        });
        return $events;
    }
}
