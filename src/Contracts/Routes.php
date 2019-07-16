<?php

namespace Larapie\Core\Contracts;

interface Routes
{
    /**
     * An array of listeners that need to be registered for the event.
     *
     * @return string[]
     */
    public function routes(array $paths): array;
}
