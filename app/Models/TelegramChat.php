<?php

namespace App\Models;

use App\Jobs\ExecuteAIResponseJob;
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
        $no_of_messages_sent=0;

        $messages=$this->telegramMessages()->orderBy('created_at','desc')->get();
        
        if(!$now)
            $now=now();

        //initialize a $start_date variable that is $no_of_days before $now
        $start_date=$now->copy()->subDays($no_of_days);

        foreach($messages as $message){
            //check to see if the message was created between $start_date and $now inclusive.
            if($message->created_at->between($start_date,$now)){
                if($message->is_incoming)
                    $no_of_messages_sent++;
            }
        }

        return $no_of_messages_sent;
    }

    public function resetBackoffPeriod(){
        //@codeCoverageIgnoreStart
        // remove the backoff flag
        // remove BACKOFF_PERIOD_IN_DAYS from configuration
        $configuration = $this->getAttribute('configuration');
        unset($configuration['BACKOFF_PERIOD_IN_DAYS']);
        $this->setAttribute('configuration', $configuration);
        $this->save();
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

        $system_context_prompt= "You are a chatbot AI assistant that is meant to help users by asking insightful questions about the topic being discussed. Keep responses in single sentences and use less than 20 words. Be more interested in the user's point of view than with sharing information or advice. Ask the user one short but insightful question. 
        
        If the user gives a good response to the question thank the user for the response, and try to expand upon what was described and go deeper with another single simple insightful question.
        
        If the user shares a strong emotions, be sure express empathy and acceptance for how the user is feeling.
        ";

        
        $prompt[]=['role'=>TelegramChat::SYSTEM_ROLE, 'content'=>$system_context_prompt];

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
        
        $final_prompt_text=isset($this->configuration['SYSTEM_CONTEXT_FINAL_PROMPT'])?$this->configuration['SYSTEM_CONTEXT_FINAL_PROMPT']:"Be as helpful as possible.|Limit your response to 20 words.|Limit your response to roughly the same length as previous user responses.";

        $final_prompts=explode('|',$final_prompt_text);
        
        $prompt[]=['role'=>TelegramChat::SYSTEM_ROLE, 'content'=>$final_prompts[array_rand($final_prompts)]];
            
        return $prompt;

        //@codeCoverageIgnoreEnd
    }

    public function triggerAIResponse(){
        // dispatch a job to execute the AI response
        //@codeCoverageIgnoreStart
        ExecuteAIResponseJob::dispatch($this)->delay(now()->addSeconds(3));
        //@codeCoverageIgnoreEnd
    }

    // function that checks if last message was outgoing
    public function wasLastMessageOutgoing(){
        
        $last_message=$this->telegramMessages()->orderBy('created_at','desc')->first();
        if($last_message){
            return $last_message->is_outgoing?true:false;
        }
        return false;
    }

    public function executeAIResponse(){
        //@codeCoverageIgnoreStart
        if(isset($this->configuration['AI_DISABLED'])&&$this->configuration['AI_DISABLED']){
            $this->sendMessage("AI is disabled.", TelegramChat::ANNOUNCEMENT_ROLE);
        }else{
            // do a check to ensure we don't spam the user with two outgoing messages in a row. If the last message was outgoing, don't send another outgoing message.
            if(!$this->wasLastMessageOutgoing()){
                $data['prompt']=$this->generatePrompt();

                $data['result'] = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages'=> $data['prompt'],
                ]);
                
                $result_text=trim($data['result']['choices'][0]['message']['content'] ?? "");
                
                if($result_text){
                    $this->sendMessage($result_text, TelegramChat::ASSISTANT_ROLE, $data);
                }
            }
            
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

    public function sendSticker($sticker_file_id='CAACAgIAAxkBAAEBVKj3ZYB-JpIbw2t0YgK8Y_W7-YLwAAJgQ', $from_username='', $data=[]){
        //@codeCoverageIgnoreStart
        $telegram_send_package=[
            'chat_id' => $this->tg_chat_id,
            'sticker' => $sticker_file_id,
        ];

        $response = Telegram::sendSticker($telegram_send_package);

        $data['telegram_send_package']=$telegram_send_package;
        $data['telegram_response']=$response;

        $this->telegramMessages()->create([
            'data'=>$data,
            'text'=>"sticker: ".$sticker_file_id,
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

    public function generateSummary($no_days_of_historical_messages_to_use=7,$max_messages=100){
        //@codeCoverageIgnoreStart
        if(isset($this->configuration['AI_DISABLED'])&&$this->configuration['AI_DISABLED']){
            $this->sendMessage("AI is disabled.", TelegramChat::ANNOUNCEMENT_ROLE);
        }else{
    
            // do a check to ensure we don't spam the user with two outgoing messages in a row. If the last message was outgoing, don't send another outgoing message.
            
            $data=[
                'model' => 'text-davinci-003',
                'prompt'=>"[INSTRUCTION] Identify and extract a maximum of 3 key issues that are suitable for discussion relevant for a coaching context. For each issue, headline it, and write a short summary of the issue key points discussed. Use a maximum of 10 words for each issue. Issues should be ranked and ordered according to the number of messages touching on each issue. This means that the most talked about issue should be item 1, second most talked about issue should be item 2, etc. If there is insufficient information from the messages, indicate that there is insufficient information for a summary to be executed. Do not use the issues in the sample format if the issues do not arise in the messages. \n
                
                The messages are as follows: \n
                [MESSAGES] \n",
                'max_tokens'=>2000,
            ];

            
            $messages=$this->telegramMessages()->where('is_incoming',true)->where('created_at','>=',now()->subDays($no_days_of_historical_messages_to_use))->orderBy('created_at','desc')->limit($max_messages)->get();

            //reverse the messages so that the most recent message is last
            $messages=$messages->reverse();
            $message_prompts=[];
            foreach($messages as $message){
                if(isset($this->configuration['NEW_CONTEXT_PROMPT'])&&$message->text==$this->configuration['NEW_CONTEXT_PROMPT']){
                    $message_prompts=[];
                }else{
                    $message_prompts[]=$message->text;
                }
            }

            for($i=0;$i<sizeof($message_prompts);$i++){
                $data['prompt'].=($i+1).'. '.$message_prompts[$i]."\n";
                
            }

            $data['prompt'].="\n\n
            [INSTRUCTION] A sample format is as follows:\n\n

            \n\nExample below:\"\"\"
            \n\n[SAMPLE FORMAT OF SUMMARY]
            \n1. Stress at work - You spoke about feeling stress from managing the stakeholders of project A.
            \n2. Family relationships - You asked for suggestions on how to improve your relationship with your father. 
            \n3. Career - You spoke about your desire to change your career.

            \n\n[SUMMARY]";
            
            $result = OpenAI::completions()->create($data);
            
            $data['result']=$result;

            $result_text="Thanks for the conversation! I hope you found it useful.";
            
            // check to see if $result['choices'][0]['text'] contains the words "Insufficient information", being case insensitive.
            if(!str_contains(strtolower($result['choices'][0]['text'] ?? ""),"insufficient information")){
                 $result_text.="

Here's a quick summary of the topics covered:\n\n".trim($result['choices'][0]['text'] ?? "");
            }

            $result_text.="

";

            $feedback_messages=[];
            $feedback_messages[]="On a scale of 1 to 10, how would you rate the conversation? 1 being the worst, 10 being the best.";
            $feedback_messages[]="Please rate the conversation on a scale of 1 to 10, 1 being the worst, 10 being the best.";
            $feedback_messages[]="Did you find the conversation useful?";
            $feedback_messages[]="If you have any feedback for me, please let me know!";
            $feedback_messages[]="Do you have any suggestions on how I could improve?";
            
            $result_text.=$feedback_messages[array_rand($feedback_messages)];
            
            if($result_text){
                $this->sendMessage($result_text, TelegramChat::ASSISTANT_ROLE, $data);
            }
            
        }

        //@codeCoverageIgnoreEnd
    }

    public function getLastActivity(){
        $lastMessage=$this->telegramMessages()->orderBy('created_at','desc')->first();
        if($lastMessage){
            return $lastMessage->created_at;
        }else{
            //@codeCoverageIgnoreStart
            return $this->created_at;
            //@codeCoverageIgnoreEnd
        }
    }

    public function performReacquistion($now=null,$test_mode=false){
        if(!$now){
            //@codeCoverageIgnoreStart
            $now = now();
            //@codeCoverageIgnoreEnd
        }
        //get the backoff period
        $backoffPeriodInDays = isset($this->configuration['BACKOFF_PERIOD_IN_DAYS'])?$this->configuration['BACKOFF_PERIOD_IN_DAYS']:2;

        if($this->getLastActivity()->diffInDays($now) >= $backoffPeriodInDays){
            //send a message to the user
            if(!$test_mode){
                //@codeCoverageIgnoreStart
                $this->sendMessage($this->getRandomReacquisitionMessage());
                //@codeCoverageIgnoreEnd
            }
            //multiply the backoff period by 2
            $backoffPeriodInDays = $backoffPeriodInDays * 2;
            
            //update the record
            $this->setAttribute('configuration', array_merge(is_array($this->configuration)?$this->configuration:[], ['BACKOFF_PERIOD_IN_DAYS' => $backoffPeriodInDays]));
            $this->save();

            return true;
        }

        return false;
    }

    public function getRandomReacquisitionMessage(){
        //@codeCoverageIgnoreStart
        $possible_messages=[
            "Hi! Is there something you would like to talk about?",
            "Hello! What's on your mind?",
            "Hey! How's life been treating you?",
            "Hi! How are you doing?",
            "Hey! How's it going?",
        ];

        return $possible_messages[array_rand($possible_messages)];

        //@codeCoverageIgnoreEnd

    }

    public function isActiveJournal(){
        if(isset($this->configuration['ACTIVE_JOURNAL'])&&($this->configuration['ACTIVE_JOURNAL']===true||strcasecmp($this->configuration['ACTIVE_JOURNAL'],'true')===0)){
            return true;
        }

        return false;
    }
}
