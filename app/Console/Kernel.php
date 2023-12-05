<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Nova\Trix\PruneStaleAttachments;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // @codeCoverageIgnoreStart
        $schedule->command('schedule:daily')->dailyAt('01:00');

        $schedule->command('journal_entry:send')->dailyAt('07:00');

        $schedule->command('reacquisition:send')->cron('0 12 * * 2-6');

        $schedule->call(new PruneStaleAttachments)->daily();
        // @codeCoverageIgnoreEnd
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
