<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     *  the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('score:record-daily')
            ->dailyAt('00:00')
            ->timezone('UTC')
            ->withoutOverlapping();

        $schedule->command('score:update-daily')
            ->dailyAt('01:00')
            ->timezone('UTC')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
