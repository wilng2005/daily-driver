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

    //declare a constant NEW_CONTEXT property
    const NEW_CONTEXT = "...";

    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function extract_and_store_chat_and_message_details(){
         //@codeCoverageIgnoreStart
        info("TelegramUpdate->extract_and_store_chat_and_message_details() start");
        info($this->data);
        
        $telegram_chat=null;

        if(isset($this->data['message']['chat'])){
            $telegram_chat = TelegramChat::updateOrCreate(
                ['tg_chat_id'=>$this->data['message']['chat']['id']],
                ['data'=>$this->data['message']['chat']]
            );

            $telegram_chat->telegramMessages()->create([
                'data'=>$this->data['message'],
                'text'=>$this->data['message']['text'] ?? "",
                'is_incoming'=>true,
                'is_outgoing'=>false,
                'from_username'=>TelegramChat::USER_ROLE,
            ]);
        }
        
        info("TelegramUpdate->extract_and_store_chat_and_message_details() end");

        return $telegram_chat;
        //@codeCoverageIgnoreEnd
    }

    
}
