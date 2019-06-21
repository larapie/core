<?php

namespace Larapie\Core\Contracts;

interface Listeners
{
    /**
     * An array of listeners that need to be registered for the event.
     *
     * @return string[]
     */
    public function listeners(): array;
}
