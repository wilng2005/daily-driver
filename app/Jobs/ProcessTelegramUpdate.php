<?php

namespace App\Jobs;

use App\Models\TelegramUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTelegramUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //@codeCoverageIgnoreStart
    public function __construct(
        public TelegramUpdate $telegramUpdate
    ) {

    }
    //@codeCoverageIgnoreEnd

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //@codeCoverageIgnoreStart

        //update chat and message storage in database
        $telegram_chat = $this->telegramUpdate->extract_and_store_chat_and_message_details();

        if ($telegram_chat) {
            // remove the backoff flag
            // remove BACKOFF_PERIOD_IN_DAYS from configuration
            $telegram_chat->resetBackoffPeriod();

            //trigger AI response
            $telegram_chat->triggerAIresponse();
        }

        //@codeCoverageIgnoreEnd
    }
}
