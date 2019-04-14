<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var string[]
     */
    protected $commands = [];

    /**
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule) : void
    {
    }

    protected function commands()
    {
    }
}
