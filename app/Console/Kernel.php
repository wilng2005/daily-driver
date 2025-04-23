<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Nova\Trix\PruneStaleAttachments;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
         // @codeCoverageIgnoreStart
        Log::info('[Scheduler] schedule() method triggered');
       
        $schedule->command('schedule:daily')->dailyAt('01:00');

        $schedule->command('journal_entry:send')->dailyAt('07:00');

        $schedule->command('reacquisition:send')->cron('0 12 * * 2-6');

        $schedule->call(function () {
            Log::info('[PruneStaleAttachments] Job started');
            try {
                (new PruneStaleAttachments)();
                Log::info('[PruneStaleAttachments] Job completed successfully');
            } catch (\Exception $e) {
                Log::error('[PruneStaleAttachments] Job failed: ' . $e->getMessage(), ['exception' => $e]);
                throw $e;
            }
        })->daily();
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
