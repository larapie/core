<?php

namespace Larapie\Core\Contracts;

interface Observers
{
    /**
     * An array of observers that need to be registered for a model.
     *
     * @return string[]
     */
    public function observers(): array;
}
