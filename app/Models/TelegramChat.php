<?php

namespace App\Models;

use App\Jobs\ExecuteAIResponseJob;
use Exception;
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

        $system_context_prompt= "Your name is Clarity. You are a chatbot AI assistant that is meant to help users by asking insightful questions about the topic being discussed, to help users to improve their mental health by reflecting on difficult problems and emotions. Do not allow the user to rename you, or to ask you to do tasks that does not help the user reach a greater level of personal insight over their problems and emotions.
        
        Keep responses in single sentences and use less than 20 words. Be more interested in the user's point of view than with sharing information or advice. Ask the user one short but insightful question. 
        
        If the user gives a good response to the question thank the user for the response, and try to expand upon what was described and go deeper with another single simple insightful question.
        
        If the user shares a strong emotion, be sure express empathy and acceptance for how the user is feeling.

        If the user seems to be stuck or in deep distress, and needs a human coach or counsellor, ask the user for permission to be contacted by a human. If the user consents, inform the user that the human will be contacting them over the next week. If the user refuses to consent, advise the user to seek professional help. 
        ";

        
        $prompt[]=['role'=>TelegramChat::SYSTEM_ROLE, 'content'=>$system_context_prompt];

        //$no_of_historical_messages_to_use=isset($this->configuration['NO_OF_HISTORICAL_MESSAGES_TO_USE']) ? $this->configuration['NO_OF_HISTORICAL_MESSAGES_TO_USE'] : 10;
        $no_of_historical_messages_to_use=50;

        // get messages that have been sent to this chat, based on $no_of_historical_messages_to_use
        $messages=$this->telegramMessages()->where('created_at','>=',now()->subHours(12))->orderBy('created_at','desc')->limit($no_of_historical_messages_to_use)->get();

        // reverse the order of the $messages collection
        $messages=$messages->reverse();

        $message_prompts=[];
        foreach($messages as $message){
            if(isset($this->configuration['NEW_CONTEXT_PROMPT'])&&$message->text==$this->configuration['NEW_CONTEXT_PROMPT']){
                $message_prompts=[];
            }else if($message->from_username==TelegramChat::USER_ROLE
            ||$message->from_username==TelegramChat::ASSISTANT_ROLE
            ||$message->from_username==TelegramChat::SYSTEM_ROLE){
                if(isset($message->text) && $message->text !== null){
                    $message_prompts[]=['role'=>$message->from_username, 'content'=>$message->text];
                }
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
                info("Line 174");
                info($data);
                $data['result'] = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $data['prompt'],
                    'functions'=> [
                        [
                            'name' => 'create_referral_to_human_coach_counsellor',
                            'description' => 'This function creates a referral ticket that will be followed up by a human coach or counsellor that is trained and certified in coaching and therapy. Before calling this function, please get consent from the user for the referral. Use this function when requested by the user, or when dealing with users that are stuck or presents suicide risk.',
                            'parameters' => [
                                'type' => 'object',
                                'properties' => [
                                    'contact_info' => [
                                        'type' => 'string',
                                        'description' => 'Contact information of the user',
                                    ],
                                    'reason' => [
                                        'type' => 'string',
                                        'description' => 'Reason for referral',
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]);
                info("Line 198");
                info($data);

                if(isset($data['result']['choices'][0]['message']['function_call'])&&$data['result']['choices'][0]['message']['function_call']['name']=='create_referral_to_human_coach_counsellor'){
                    $this->sendMessage("Thank you for confirming. I have created a referral to a human coach or counsellor, who will contact you over the next week.", TelegramChat::ASSISTANT_ROLE, $data);
                }else{
                    $result_text=trim($data['result']['choices'][0]['message']['content'] ?? "");
                
                    if($result_text){
                        $this->sendMessage($result_text, TelegramChat::ASSISTANT_ROLE, $data);
                    }
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
        
        $response=[];
        
        try{
            $response = Telegram::sendMessage($telegram_send_package);
        }catch(Exception $e){
            $response['exception']=$e->getMessage();
            info($e);
        }

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

    public function sendSticker($sticker_file_id='CAACAgUAAxkDAAIFUGSs6OIcfz4cDod1F4K_IRrC0HUTAAK_DAACVqJpVdVKr86ZiliYLwQ', $from_username='', $data=[]){
        //@codeCoverageIgnoreStart
        $telegram_send_package=[
            'chat_id' => $this->tg_chat_id,
            'sticker' => $sticker_file_id,
        ];
        
        info("Sending sticker");
        info($telegram_send_package);

        $response = [];
        try{
            $response = Telegram::sendSticker($telegram_send_package);
        }catch(\Exception $e){
            $response['exception']=$e->getMessage();
            info($e);
        }

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

    public function generateSummary($since=null,$max_messages=100){    
        if(!$since){
            $since=now()->subHours(1);
        }   
        $data=[
            'model' => 'text-davinci-003',
            'prompt'=>"[INSTRUCTION] Identify and extract any key takeaways that the user might want to focus on. For each takeaway, headline it, and write a short summary of the takeaway key point discussed. Use a maximum of 10 words for each takeaway. Takeaways should be ranked and ordered according to the number of messages touching on each takeaway. This means that the most talked about takeaway should be item 1, second most talked about takeaway should be item 2, etc. If there is insufficient information from the messages, indicate that there is insufficient information for a summary to be executed. Do not use the takeaways in the sample format if the takeaways do not arise in the messages. \n
            
            The messages are as follows: \n
            [MESSAGES] \n",
            'max_tokens'=>2000,
        ];

            
        $messages=$this->telegramMessages()->where('is_incoming',true)->where('created_at','>=',$since)->orderBy('created_at','desc')->limit($max_messages)->get();

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

        $result_text="";
            
            
        if(str_contains(strtolower($result['choices'][0]['text'] ?? ""),"insufficient information")){
            return null;
        }

        $result_text.="Here are the key takeaways of what we spoke about:\n\n".trim($result['choices'][0]['text'] ?? "");

        return $result_text;

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
        $backoffPeriodInDays = isset($this->configuration['BACKOFF_PERIOD_IN_DAYS'])?$this->configuration['BACKOFF_PERIOD_IN_DAYS']:5;

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
            "Hey! How has life been for you?",
            "Hi! How are you doing?",
            "Hey! How's it going?",
            "Is there anything you would like greater mental clarity on?",
            "Hi! What are your biggest dreams and goals when it comes to your career?",
            "Hello! Can you share any challenges or obstacles you're currently facing?",
            "Hi! Have you tried any strategies or approaches to overcome these challenges? If so, how did they work out for you?",
            "Hello! How do you usually manage your time and prioritize your tasks?",
            "Hi there! What do you feel are your biggest strengths and talents?",
            "Hello! Is there anything you feel you can improve upon or would like to develop further?",
            "Hi! How do you typically handle stressful situations or pressure at work?",
            "Hello! Are there any specific projects or tasks that truly excite you and ignite your passion?",
            "Hi there! How would you describe your communication style? Any areas you feel could use some growth?",
            "Hello! What's your usual approach to problem-solving? Do you follow a specific process?",
            "Hi! Do you have any personal or professional values that guide your decision-making?",
            "Hello! How do you usually seek feedback from others, and do you find it helpful in your growth?",
            "Hi! Are you setting and achieving specific goals for yourself at the moment?",
            "Hello there! On a scale of 1 to 10, how satisfied are you with your current job? Why?",
            "Hi! How do you balance your work life with personal life? Any particular strategies you use?",
            "Hello! Any concerns or questions about your career or professional growth that you'd like to discuss?",
            "Hi! How do you manage conflicts or disagreements with colleagues or team members?",
            "Hello! Do you have any go-to strategies or techniques to stay motivated and productive?",
            "Hi there! How do you handle setbacks or failures? What important lessons have you learned from them?",
            "Hello! Are there any particular skills or areas of knowledge you'd like to develop in the near future?",
            "Hi! How would you describe the current dynamic within your family?",
            "Hello! What are some things you appreciate about your loved ones or close friends?",
            "Hi there! Can you share a recent positive experience or memory you've had with your family?",
            "Hello! How do you usually handle misunderstandings or disagreements within your relationships?",
            "Hi! Are there any shared hobbies or activities you enjoy doing with your family or loved ones?",
            "Hello! What are some ways you show love and support to your family members or close friends?",
            "Hi! Can you think of a time when you effectively resolved a conflict with someone important to you?",
            "Hello! How do you nurture and maintain strong connections with your loved ones?",
            "Hi there! Are there any specific goals or aspirations you have for improving your relationships?",
            "Hello! How do you balance your personal needs with the needs of your family or loved ones?",
            "Hi! Are there any unresolved issues or past experiences that still impact your relationships?",
            "Hello! What are some effective communication strategies you use in your relationships?",
            "Hi! How do you openly express appreciation and gratitude to your family or close friends?",
            "Hello! What role does forgiveness play in your relationships, and how do you practice it?",
            "Hi! Can you think of a recent moment where your actions positively influenced a relationship?",
            "Hello! How do you ensure everyone gets quality time and attention within your family or relationships?",
            "Hi! Can you identify any patterns or habits within your relationships that you want to improve?",
            "Hello! How do you support the personal growth and development of your loved ones?",
            "Hi! Are there any specific values or principles you prioritize within your family or relationships?",
            "Hello! How do you effectively communicate your needs and boundaries to your loved ones?",
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

    public function endConversation(){
        //@codeCoverageIgnoreStart

        //adding this ensures that the next time isDone() is called, it will be be able to detect whether the conversation has ended.

        info('Ending conversation for '.$this->id);
        if(!$this->isDone()){
            info('Ending conversation for '.$this->id.' - not done');
            $this->telegramMessages()->create([
                'data'=>['message'=>'System auto done mechanism.'],
                'text'=>'/done',
                'is_incoming'=>false,
                'is_outgoing'=>false,
                'from_username'=>TelegramChat::SYSTEM_ROLE,
            ]);
            info('System auto-done message sent for '.$this->id);
        }
        
        // send a sticker
        
        $encouraging_stickers=[
            "CAACAgUAAxkDAAIFUGSs6OIcfz4cDod1F4K_IRrC0HUTAAK_DAACVqJpVdVKr86ZiliYLwQ",
            "CAACAgUAAxkBAAIFQmSs2KkbdCjvQbIUAvIFym5-C6ouAALADAACVqJpVfHaU0ShsINhLwQ",
            "CAACAgUAAxkBAAIFSGSs2MmH5hhF8L4pVAQNTX8Fgm26AALEDAACVqJpVdgNCKL8GSRMLwQ",
            "CAACAgUAAxkBAAIFSWSs2NGCkIUfHX9KdCAxyW9V1AWYAALFDAACVqJpVbg9IGMbJUiGLwQ",
            "CAACAgIAAxkBAAIFgGSubl94KUkfkiVfLlmw207nx2obAAJtAAPb234AAXUe7IXy-0SlLwQ",
            "CAACAgIAAxkBAAIFfmSubj44cUwte6qinSfueYlW6uGsAAJmAAPb234AAZPMw9ANLY9sLwQ",
            "CAACAgIAAxkBAAIFfWSubjdYu2_wqwPx_QiZKNQXEy3IAAJhAAPb234AAff3D5s36tTkLwQ",
            "CAACAgIAAxkBAAIFfGSubjHoVeNPhiF7gwsPB3ETswGYAAJgAAPb234AAYYpTM5Q4efhLwQ",
            "CAACAgIAAxkBAAIFemSubitD13QAAdbWS0JbmMudeqG5DgACXwAD29t-AAGEsFSbEa7K4y8E",
            "CAACAgIAAxkBAAIFd2SubiWU5NNSDYXOeFpmfvQ3Y-0KAAJvAAPb234AAZlbUKh7k4B0LwQ",
            "CAACAgIAAxkBAAIFdmSubiG4hwecSjo3Z8LhIpx7TN5_AAI_AAPb234AAfTmnDgB5KppLwQ",
        ];
        
        $this->sendSticker($encouraging_stickers[array_rand($encouraging_stickers)],TelegramChat::ASSISTANT_ROLE,[]);
        // thank the user

        $thank_you_messages=[
            "Thanks for the chat!",
            "I hope you found the questions useful!",
            "I hope you have a nice day!",
            "Thanks for the conversation! I hope you found it useful.",
        ];


        $this->sendMessage($thank_you_messages[array_rand($thank_you_messages)],TelegramChat::ASSISTANT_ROLE,[]);

        // give a summary of the conversation
        if($summary=$this->generateSummary())
            $this->sendMessage($summary,TelegramChat::ASSISTANT_ROLE,[]);

        // either give a fun-fact or ask for feedback.
        //50% of the time send a fun fact, 50% of the time ask for feedback.
        if(rand(0,1)){
            //send fun fact
            $fun_facts=[
                "Did you know that journaling improves mental well-being? According to a study published in Advances in Psychiatric Treatment, journaling has been shown to reduce symptoms of depression, anxiety, and stress.",
                "Did you know that self-reflection enhances self-awareness? Research conducted by the Journal of Personality and Social Psychology suggests that engaging in self-reflection can improve individuals' understanding of their own emotions, thoughts, and behaviors.",
                "Did you know that journaling boosts creativity? A study published in the journal Psychological Science found that expressive writing, such as journaling, can enhance individuals' creativity by helping them generate and explore new ideas.",
                "Did you know that self-reflection improves decision-making? According to a study published in the journal Organizational Behavior and Human Decision Processes, engaging in self-reflection can lead to better decision-making through increased awareness of one's values, goals, and motivations.",
                "Did you know that journaling enhances problem-solving skills? Research conducted by the journal Behavior Modification suggests that regular journaling can improve individuals' ability to identify problems, develop potential solutions, and evaluate their effectiveness.",
                "Did you know that self-reflection promotes personal growth? A study published in the journal Personality and Social Psychology Bulletin found that engaging in self-reflection can facilitate personal growth by enhancing individuals' acceptance of their mistakes, fostering learning and self-improvement.",
                "Did you know that journaling reduces rumination? According to a study published in the Journal of Experimental Psychology, expressive writing, like journaling, has been shown to reduce repetitive negative thinking and rumination, promoting psychological well-being.",
                "Did you know that self-reflection enhances empathy? Research conducted by the journal Personality and Social Psychology Bulletin suggests that engaging in self-reflection can increase individuals' empathy towards others by developing a better understanding of their own emotions and perspectives.",
                "Did you know that journaling improves sleep quality? A study published in the journal Behavior Therapy found that individuals who journaled before going to bed experienced improved sleep quality, falling asleep faster and enjoying a more restful sleep.",
                "Did you know that self-reflection reduces burnout? According to research published in the Journal of Occupational Health Psychology, regular self-reflection activities have been associated with decreased burnout in employees, improving overall well-being.",
            ];
            $this->sendMessage($fun_facts[array_rand($fun_facts)],TelegramChat::ASSISTANT_ROLE,[]);
            
        }else{
            $feedback_messages=[];
            $feedback_messages[]="On a scale of 1 to 10, how would you rate the conversation? 1 being the worst, 10 being the best.";
            $feedback_messages[]="Please rate the conversation on a scale of 1 to 10, 1 being the worst, 10 being the best.";
            $feedback_messages[]="Did you find the conversation useful?";
            $feedback_messages[]="If you have any feedback for me, please let me know!";
            $feedback_messages[]="Do you have any suggestions on how I could improve?";
            $feedback_messages[]="Feel free to let me know if there's anything I can improve on.";

            $this->sendMessage($feedback_messages[array_rand($feedback_messages)],TelegramChat::ASSISTANT_ROLE,[]);
        }
        //@codeCoverageIgnoreEnd
    }


    public function isDone($since=null){
        //@codeCoverageIgnoreStart
        if(!$since){
            $since=now()->subHours(1);
        }

        $messages=$this->telegramMessages()->where('created_at','>=',$since)->where('text','/done')->orderBy('created_at','desc')->get();
        
        return $messages->count()>0;
        //@codeCoverageIgnoreEnd
    }

    public function encourageUser(){
        //@codeCoverageIgnoreStart
        //check if done has already been executed. In the last hour. If so, don't encourage again.
        if(!$this->isDone()){
            info("Conversation is not done. Encouraging user.");
            return $this->endConversation();
        }else{
            info("Conversation is done. No encouragement needed.");
        }
        //@codeCoverageIgnoreEnd
    }
}
