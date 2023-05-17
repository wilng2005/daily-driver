<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Nova\Actions\Actionable;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramChat extends Model
{
    use Actionable,HasFactory;

    const ANNOUNCEMENT_ROLE='announcement';
    const SYSTEM_ROLE='system';
    const ASSISTANT_ROLE='assistant';
    const USER_ROLE='user';

    //the tg_chat_id value is used by the  bot api to identify the chat.
    protected $fillable = ['data','tg_chat_id','configuration'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'configuration' => 'array',
    ];

    public function telegramMessages(): HasMany
    {
        //@codeCoverageIgnoreStart
        return $this->hasMany(TelegramMessage::class);
        //@codeCoverageIgnoreEnd
    }

    // a method that returns the number of messages that have been sent to this chat over the past 7 days
    public function getNoOfMessagesSentOverPeriod($no_of_days,$now=null){
        //@codeCoverageIgnoreStart
        $no_of_messages_sent=0;

        $messages=$this->telegramMessages()->where('is_incoming')->orderBy('created_at','desc')->get();
        
        if(!$now)
            $now=now();

        foreach($messages as $message){
            if($message->created_at->diffInDays($now)<=$no_of_days){
                $no_of_messages_sent++;
            }else{
                break;
            }
        }

        return $no_of_messages_sent;
        //@codeCoverageIgnoreEnd
    }

    public function executeSystemPrompt($text, $data=[]){
        //@codeCoverageIgnoreStart

        $this->telegramMessages()->create([
            'data'=>$data,
            'text'=>$text,
            'is_incoming'=>false,
            'is_outgoing'=>false,
            'from_username'=>TelegramChat::SYSTEM_ROLE,
        ]);

        $this->triggerAIResponse();
        
        //@codeCoverageIgnoreEnd
    }

    public function generatePrompt(){
        //@codeCoverageIgnoreStart
        $prompt=[];

        if(isset($this->configuration['SYSTEM_CONTEXT_PROMPT']))
            $prompt[]=['role'=>TelegramChat::SYSTEM_ROLE, 'content'=>$this->configuration['SYSTEM_CONTEXT_PROMPT']];

        $no_of_historical_messages_to_use=isset($this->configuration['NO_OF_HISTORICAL_MESSAGES_TO_USE']) ? $this->configuration['NO_OF_HISTORICAL_MESSAGES_TO_USE'] : 10;

        // get messages that have been sent to this chat, based on $no_of_historical_messages_to_use
        $messages=$this->telegramMessages()->orderBy('created_at','desc')->limit($no_of_historical_messages_to_use)->get();

        // reverse the order of the $messages collection
        $messages=$messages->reverse();

        $message_prompts=[];
        foreach($messages as $message){
            if(isset($this->configuration['NEW_CONTEXT_PROMPT'])&&$message->text==$this->configuration['NEW_CONTEXT_PROMPT']){
                $message_prompts=[];
            }else if($message->from_username==TelegramChat::USER_ROLE
            ||$message->from_username==TelegramChat::ASSISTANT_ROLE
            ||$message->from_username==TelegramChat::SYSTEM_ROLE){
                $message_prompts[]=['role'=>$message->from_username, 'content'=>$message->text];
            }
        }

        // merge $prompt and $message_prompts
        $prompt=array_merge($prompt,$message_prompts);

        return $prompt;

        //@codeCoverageIgnoreEnd
    }

    public function triggerAIResponse(){

        //@codeCoverageIgnoreStart
        if(isset($this->configuration['AI_ENABLED'])&&$this->configuration['AI_ENABLED'])
        {
            $data['prompt']=$this->generatePrompt();

            $data['result'] = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages'=> $data['prompt'],
            ]);
            
            $result_text=trim($data['result']['choices'][0]['message']['content'] ?? "");
            
            if($result_text){
                $this->sendMessage($result_text, TelegramChat::ASSISTANT_ROLE, $data);
            }
        }else{
            $this->sendMessage("AI is disabled.", TelegramChat::ANNOUNCEMENT_ROLE);
        }

        //@codeCoverageIgnoreEnd
    }

    public function sendJournalEntry($data=[]){
        //@codeCoverageIgnoreStart
        if(isset($this->configuration['ACTIVE_JOURNAL'])&&$this->configuration['ACTIVE_JOURNAL']&&isset($this->configuration['JOURNAL_ENTRY_PROMPT']))
        {
            
            $this->telegramMessages()->create([
                'data'=>$data,
                'text'=>$this->configuration['JOURNAL_ENTRY_PROMPT'],
                'is_incoming'=>false,
                'is_outgoing'=>false,
                'from_username'=>TelegramChat::SYSTEM_ROLE,
            ]);

            $this->triggerAIResponse();
        }
        
        //@codeCoverageIgnoreEnd
    }

    public function sendMessage($text, $from_username='', $data=[]){
        //@codeCoverageIgnoreStart
        $telegram_send_package=[
            'chat_id' => $this->tg_chat_id,
            'text' => $text
        ];

        $response = Telegram::sendMessage($telegram_send_package);

        $data['telegram_send_package']=$telegram_send_package;
        $data['telegram_response']=$response;

        $this->telegramMessages()->create([
            'data'=>$data,
            'text'=>$text,
            'is_incoming'=>false,
            'is_outgoing'=>true,
            'from_username'=>$from_username,
        ]);
        
        return $response;
        //@codeCoverageIgnoreEnd
    }
}
