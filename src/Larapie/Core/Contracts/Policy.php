<?php

namespace Larapie\Core\Contracts;

interface Policy
{
    /**
     * A Fully Qualified Name To The Policy Class.
     *
     * @return string[]
     */
    public function policy(): string;
}
