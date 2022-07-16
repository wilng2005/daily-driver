<?php

namespace App\Console\Commands;

use App\Models\Capture;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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

        $dayOfWeek=Carbon::now()->dayOfWeek;

        // @codeCoverageIgnoreStart
        if(in_array($dayOfWeek, [1,2,3,4,5])){
            foreach (Capture::all() as $capture) {
                $capture->weekday_schedule();
            }
        }
        // @codeCoverageIgnoreEnd
    }
}
