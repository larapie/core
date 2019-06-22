<?php

namespace Larapie\Core\Kernels;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel;
use Larapie\Core\Contracts\Bootstrapping;

class ConsoleKernel extends Kernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->bootModuleSchedules($schedule);
    }

    protected function bootModuleSchedules(Schedule $schedule): void
    {
        $bootstrap = $this->app->make(Bootstrapping::class);

        foreach ($bootstrap->getProviders() as $provider) {
            if ($provider['schedule']) {
                instance_without_constructor($provider['fqn'])->schedule($schedule);
            }
        }
    }
}
