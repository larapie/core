<?php


namespace Larapie\Core\Internals;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Larapie\Core\Larapie\Core\Contracts\Bootstrapping;

class ScheduleKernel
{
    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * @var Bootstrapping
     */
    protected $bootstrap;

    public function __construct(Application $app, Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->bootstrap = $app->make(Bootstrapping::class);
    }

    public function boot()
    {
        foreach ($this->bootstrap->getProviders() as $provider) {
            if ($provider['schedule'])
                instance_without_constructor($provider['fqn'])->schedule($this->schedule);
        }
    }
}
