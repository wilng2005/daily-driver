<?php

namespace App\Jobs;

use App\Models\TelegramChat;
use App\Models\TelegramUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

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
    )
    {
        
        
    }
    //@codeCoverageIgnoreEnd

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //@codeCoverageIgnoreStart
        info("ProcessTelegramUpdate handle commenced.");
        
        //update chat and message storage in database
        $telegram_chat=$this->telegramUpdate->extract_and_store_chat_and_message_details();

        if($telegram_chat){
            // remove the backoff flag
            // remove BACKOFF_PERIOD_IN_DAYS from configuration
            $telegram_chat->resetBackoffPeriod();

            //trigger AI response
            $telegram_chat->triggerAIresponse();
        }
            
            
        //@codeCoverageIgnoreEnd
    }
}
