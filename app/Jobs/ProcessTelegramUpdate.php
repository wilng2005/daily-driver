<?php

namespace App\Jobs;

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
        $message_text=$this->telegramUpdate->data->message->text ?? "";
        info($message_text);
        if($message_text){
            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => 'Me:'.$message_text." \nChatGPT:",
                'max_tokens' => 1024
            ]);

            $response = Telegram::sendMessage([
                'chat_id' => $this->telegramUpdate->data->message->chat->id,
                'text' => $result['choices'][0]['text'],
            ]);
        
            info('message_id:'.$response->getMessageId());
        }
        //@codeCoverageIgnoreEnd
    }
}
