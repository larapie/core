<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\ClassResource;
use Larapie\Core\Contracts\Schedule;

class ProviderResource extends ClassResource
{

    public function hasSchedule()
    {
        return class_implements_interface($this->getFQN(), Schedule::class);
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), ['schedule' => $this->hasSchedule()]);
    }
}
