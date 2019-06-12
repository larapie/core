<?php

namespace Larapie\Core\Larapie\Core\Contracts;

interface Listeners
{
    /**
     * An array of listeners that need to be registered for the event.
     *
     * @return string[]
     */
    public function registerListeners(): array;
}