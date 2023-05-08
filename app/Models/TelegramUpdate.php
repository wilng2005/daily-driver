<?php

namespace App\Models;

use App\Models\TelegramChat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramUpdate extends Model
{
    use HasFactory;
    protected $fillable = ['data'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function extract_and_store_chat_and_message_details(){
        info("TelegramUpdate->extract_and_store_chat_and_message_details() start");
        info($this->data);
        
        if(isset($this->data['message']['chat'])){
            $telegram_chat = TelegramChat::updateOrCreate(
                ['tg_chat_id'=>$this->data['message']['chat']['id']],
                ['data'=>$this->data['message']['chat']]
            );
        }
        
        info("TelegramUpdate->extract_and_store_chat_and_message_details() end");
    }

    public function execute_response(){
        info("TelegramUpdate->execute_response()");

        $message_text=$this->data['message']['text'] ?? "";
        info($message_text);

        if($message_text){
            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => 'Me:'.$message_text." \nChatGPT:",
                'max_tokens' => 1024
            ]);

            $response = Telegram::sendMessage([
                'chat_id' => $this->data['message']['chat']['id'],
                'text' => $result['choices'][0]['text'],
            ]);
        
            info('message_id:'.$response->getMessageId());
        }
    }
}
