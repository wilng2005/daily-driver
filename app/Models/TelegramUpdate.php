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

            $telegram_chat->telegramMessages()->create([
                'data'=>$this->data['message'],
                'text'=>$this->data['message']['text'] ?? "",
                'is_incoming'=>true,
                'is_outgoing'=>false,
                'from_username'=>$this->data['message']['from']['username'] ?? "",
            ]);
        }
        
        info("TelegramUpdate->extract_and_store_chat_and_message_details() end");
    }

    public function generate_prompt($telegram_chat=null){
        // get the right $telegram_chat
        if(!$telegram_chat){
            $telegram_chat = TelegramChat::where('tg_chat_id',$this->data['message']['chat']['id'])->first();
        }

        // get messages that have been sent to this chat over the past 15 minutes
        $messages = $telegram_chat->telegramMessages()->where('created_at','>',now()->subMinutes(15))->get();
        $prompt = 'Imagine you are ChatGPT.\n\n';

        foreach($messages as $message){
            if($message->is_incoming){
                $prompt.= 'User: '.$message->text."\n";
            }else{
                $prompt.= 'AI: '.$message->text."\n";
            }
        }
        
        $prompt.= 'AI:';
        
        return trim($prompt);
    }

    public function execute_response(){
        info("TelegramUpdate->execute_response()");

        $message_text=$this->data['message']['text'] ?? "";
        info($message_text);

        if($message_text){
            $prompt=$this->generate_prompt();

            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 1024
            ]);
            
            $result_text=trim($result['choices'][0]['text'] ?? "");
            $response = Telegram::sendMessage([
                'chat_id' => $this->data['message']['chat']['id'],
                'text' => $result_text,
            ]);

            $telegram_chat = TelegramChat::firstOrCreate(
                ['tg_chat_id'=>$this->data['message']['chat']['id']],
                ['data'=>$this->data['message']['chat']]
            );

            $telegram_chat->telegramMessages()->create([
                'data'=>$prompt,
                'text'=>$result_text,
                'is_incoming'=>false,
                'is_outgoing'=>true,
                'from_username'=>"",
            ]);
        
            info('message_id:'.$response->getMessageId());
        }
    }
}
