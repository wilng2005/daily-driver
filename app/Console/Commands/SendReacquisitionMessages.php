<?php

namespace App\Console\Commands;

use App\Services\TelegramChatProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReacquisitionMessages extends Command
{
    protected TelegramChatProvider $provider;

    public function __construct(TelegramChatProvider $provider)
    {
        parent::__construct();
        $this->provider = $provider;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reacquisition:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending of reacquisition messages to users who have not sent a message for a while';

    // The reacquisition strategy is to do an exponential back off, starting from 2 days.
    // i.e. first back off is 2 days.
    //     second back off is 4 days.
    //   third back off is 8 days, etc.

    // if we receive any message from the user, clear the backoff mode.

    /*
        The logic:
            first get the user's last message sent.
            check to see if the last message is more than back-off period (min 2 days).
            if it is, send a message to the user, then multiply the back-off period by 2 and update the record.
            if it is not, do nothing.
    */

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Log::info('[reacquisition:send] Job started');
        try {
            foreach ($this->provider->all() as $telegramChat) {
                $telegramChat->performReacquistion();
            }
            Log::info('[reacquisition:send] Job completed successfully');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('[reacquisition:send] Job failed: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }
}
