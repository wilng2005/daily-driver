<?php

namespace App\Console\Commands;

use App\Models\Capture;
use App\Models\DailySnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
     */
    public function handle(): int
    {
        Log::info('[schedule:daily] Job started');
        try {
            $productivity_data = $this->captureEndOfDayNumbers();

            foreach (Capture::all() as $capture) {
                $capture->daily_schedule();
            }

            $dayOfWeek = Carbon::now()->dayOfWeek;

            if (in_array($dayOfWeek, [1, 2, 3, 4, 5])) {
                foreach (Capture::all() as $capture) {
                    $capture->weekday_schedule();
                }
            }

            $productivity_data = $this->captureStartOfDayNumbers($productivity_data);
            $data['productivity'] = $productivity_data;

            $dailySnapshot = DailySnapshot::create([
                'data' => $data,
                'date' => Carbon::now()->format('Y-m-d'),
            ]);

            Log::info('[schedule:daily] Job completed successfully');
            return 0;
        } catch (\Exception $e) {
            Log::error('[schedule:daily] Job failed: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    public function captureEndOfDayNumbers($data = [])
    {

        $no_of_inbox_captures = 0;
        $no_of_next_action_captures = 0;
        $no_of_inbox_next_action_captures = 0;
        $total_no_of_captures = 0;

        //@codeCoverageIgnoreStart
        foreach (Capture::all() as $capture) {
            $total_no_of_captures++;
            if ($capture->inbox || $capture->next_action) {
                $no_of_inbox_next_action_captures++;
                if ($capture->inbox) {
                    $no_of_inbox_captures++;
                } else {
                    $no_of_next_action_captures++;
                }
            }
        }
        //@codeCoverageIgnoreEnd

        $data['end_of_day'] = [
            'no_of_inbox_captures' => $no_of_inbox_captures,
            'no_of_next_action_captures' => $no_of_next_action_captures,
            'no_of_inbox_next_action_captures' => $no_of_inbox_next_action_captures,
            'total_no_of_captures' => $total_no_of_captures,
        ];

        return $data;
    }

    public function captureStartOfDayNumbers($data = [])
    {

        $no_of_inbox_captures = 0;
        $no_of_next_action_captures = 0;
        $no_of_inbox_next_action_captures = 0;
        $total_no_of_captures = 0;

        //@codeCoverageIgnoreStart
        foreach (Capture::all() as $capture) {
            $total_no_of_captures++;
            if ($capture->inbox || $capture->next_action) {
                $no_of_inbox_next_action_captures++;
                if ($capture->inbox) {
                    $no_of_inbox_captures++;
                } else {
                    $no_of_next_action_captures++;
                }
            }
        }

        //@codeCoverageIgnoreEnd

        $data['start_of_day'] = [
            'no_of_inbox_captures' => $no_of_inbox_captures,
            'no_of_next_action_captures' => $no_of_next_action_captures,
            'no_of_inbox_next_action_captures' => $no_of_inbox_next_action_captures,
            'total_no_of_captures' => $total_no_of_captures,
        ];

        return $data;
    }
}
