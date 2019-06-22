<?php

namespace Larapie\Core\Contracts;

interface Scheduling
{
    public function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void;
}
