<?php

namespace App\Console\Commands;

use App\Models\Capture;
use Illuminate\Console\Command;

class DoDailySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggering daily for routine tasks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Capture::all() as $capture) {
            $capture->daily_schedule();
        }
    }
}
