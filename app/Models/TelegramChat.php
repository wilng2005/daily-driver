<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramChat extends Model
{
    use Actionable,HasFactory,SoftDeletes;

    const ANNOUNCEMENT_ROLE='announcement';
    const SYSTEM_ROLE='system';
    const ASSISTANT_ROLE='assistant';
    const USER_ROLE='user';
    const DEFAULT_PROMPT='Ask the user one short but insightful journaling question about increasing self-awareness and mental well-being. Do not say anything else. If the user gives a good response to the journaling question thank the user for the response, and try to expand upon what was described and go deeper with another single simple insightful question.';

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

        $messages=$this->telegramMessages()->orderBy('created_at','desc')->get();
        
        if(!$now)
            $now=now();

        foreach($messages as $message){
            if($message->created_at->diffInDays($now)<=$no_of_days){
                if($message->is_incoming)
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

    public function getJournalEntryPrompt(){
        
        if(isset($this->configuration['JOURNAL_ENTRY_PROMPT']))
        {
            // explode the string into an array of strings with | as a separator, and pick a random string from the array to return
            $journal_entry_prompts=explode('|',$this->configuration['JOURNAL_ENTRY_PROMPT']);
            return $journal_entry_prompts[array_rand($journal_entry_prompts)];
        }
        
        return TelegramChat::DEFAULT_PROMPT;
    }

    public function sendJournalEntry($data=[]){
        //@codeCoverageIgnoreStart
        if(isset($this->configuration['ACTIVE_JOURNAL'])&&$this->configuration['ACTIVE_JOURNAL'])
        {
            
            $this->telegramMessages()->create([
                'data'=>$data,
                'text'=>$this->getJournalEntryPrompt(),
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

    //write a function that is able to see if we have received any message from the user over the past X number of days, return true if there is a message, false if there is no message
    public function hasReceivedMessageFromUserOverPeriod($no_of_days=1,$now=null){
       
        $messages=$this->telegramMessages()->orderBy('created_at','desc')->get();
        
        if(!$now)
            $now=now();

        foreach($messages as $message){
            if($message->created_at->diffInDays($now)<=$no_of_days){
                if($message->is_incoming)
                    return true;
            }else{
                break;
            }
        }

        return false;
    }
}
