<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\ClassResource;
use Larapie\Core\Contracts\Observers;
use Larapie\Core\Contracts\Policy;

class ModelResource extends ClassResource
{
    protected $policy;

    protected $observers;

    protected function boot()
    {
        parent::boot();
        $this->policy = $this->extractPolicy();
        $this->observers = $this->extractObservers();
    }

    public function extractPolicy()
    {
        $policy = null;

        try {
            if (class_implements_interface($this->getFQN(), Policy::class)) {
                $policy = call_class_function($this->getFQN(), 'policy');
            }
        } catch (\Throwable $exception) {
        }

        return $policy;
    }

    /**
     * @return mixed
     */
    public function getPolicy()
    {
        return $this->policy;
    }

    public function extractObservers()
    {
        $observers = [];

        try {
            if (class_implements_interface($this->getFQN(), Observers::class)) {
                $observerClasses = call_class_function($this->getFQN(), 'observers');
            }
        } catch (\Throwable $exception) {
            $observerClasses = [];
        }
        foreach ($observerClasses ?? [] as $observerClass) {
            $observers[] = $observerClass;
        }

        return $observers;
    }

    /**
     * @return mixed
     */
    public function getObservers()
    {
        return $this->observers;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'policy'    => $this->getPolicy(),
            'observers' => $this->getObservers(),
        ]);
    }
}
