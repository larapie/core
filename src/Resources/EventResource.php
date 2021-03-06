<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\ClassResource;
use Larapie\Core\Contracts\Listeners;

class EventResource extends ClassResource
{
    protected $listeners;

    protected function boot()
    {
        parent::boot();
        $this->listeners = $this->extractListeners();
    }

    public function extractListeners()
    {
        $listeners = [];

        try {
            if (class_implements_interface($this->getFQN(), Listeners::class)) {
                $listenerClasses = call_class_function($this->getFQN(), 'listeners');
            }
        } catch (\Throwable $exception) {
            $listenerClasses = [];
        }
        foreach ($listenerClasses ?? [] as $listenerClass) {
            $listener = instance_without_constructor($listenerClass);
            if ($listener !== null && method_exists($listener, 'handle')) {
                $listeners[] = $listenerClass;
            }
        }

        return $listeners;
    }

    /**
     * @return mixed
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), ['listeners' => $this->getListeners()]);
    }

    public static function configPath(): string
    {
        return config('larapie.resources.events');
    }
}
