<?php

namespace Larapie\Core\Exceptions;

class InvalidRouteGroupException extends \RuntimeException
{
    public function __construct($group)
    {
        parent::__construct("Routegroup $group was not found. Make sure it exists in the larapie config file.");
    }
}
