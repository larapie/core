<?php


namespace Larapie\Core\Contracts;


interface Schedule
{
    public function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void;
}
