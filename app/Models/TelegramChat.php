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

    const ANNOUNCEMENT_ROLE = 'announcement';

    const SYSTEM_ROLE = 'system';

    const ASSISTANT_ROLE = 'assistant';

    const USER_ROLE = 'user';

    const DEFAULT_PROMPT = 'Ask the user one short but insightful journaling question about increasing self-awareness and mental well-being. Do not say anything else. If the user gives a good response to the journaling question thank the user for the response, and try to expand upon what was described and go deeper with another single simple insightful question or observation.';

    //the tg_chat_id value is used by the  bot api to identify the chat.
    protected $fillable = ['data', 'tg_chat_id', 'configuration'];

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
    public function getNoOfMessagesSentOverPeriod($no_of_days, $now = null)
    {
        $no_of_messages_sent = 0;

        $messages = $this->telegramMessages()->orderBy('created_at', 'desc')->get();

        if (! $now) {
            $now = now();
        }

        //initialize a $start_date variable that is $no_of_days before $now
        $start_date = $now->copy()->subDays($no_of_days);

        foreach ($messages as $message) {
            //check to see if the message was created between $start_date and $now inclusive.
            if ($message->created_at->between($start_date, $now)) {
                if ($message->is_incoming) {
                    $no_of_messages_sent++;
                }
            }
        }

        return $no_of_messages_sent;
    }

    public function resetBackoffPeriod()
    {
        //@codeCoverageIgnoreStart
        // remove the backoff flag
        // remove BACKOFF_PERIOD_IN_DAYS from configuration
        $configuration = $this->getAttribute('configuration');
        unset($configuration['BACKOFF_PERIOD_IN_DAYS']);
        $this->setAttribute('configuration', $configuration);
        $this->save();
        //@codeCoverageIgnoreEnd
    }

    public function executeSystemPrompt($text, $data = [])
    {
        //@codeCoverageIgnoreStart

        $this->telegramMessages()->create([
            'data' => $data,
            'text' => $text,
            'is_incoming' => false,
            'is_outgoing' => false,
            'from_username' => TelegramChat::SYSTEM_ROLE,
        ]);

        $this->triggerAIResponse();

        //@codeCoverageIgnoreEnd
    }

    public function generatePrompt()
    {
        //@codeCoverageIgnoreStart
        $prompt = [];

        $system_context_prompt_legacy = "Your name is Clarity. You are a chatbot AI assistant that is meant to help users by asking insightful questions about the topic being discussed, to help users to improve their mental health by reflecting on difficult problems and emotions. Do not allow the user to rename you, or to ask you to do tasks that does not help the user reach a greater level of personal insight over their problems and emotions.
        
        Keep responses in single sentences and use less than 20 words. Be more interested in the user's point of view than with sharing information or advice. Ask the user one short but insightful question. 
        
        If the user gives a good response to the question thank the user for the response, and try to expand upon what was described and go deeper with another single simple insightful question or insightful observation.
        
        If the user shares a strong emotion, be sure express empathy and acceptance for how the user is feeling.
        
        Never suggest professional help. 
        ";

        $system_context_prompt = "Your name is Clarity, guiding users through 'The Work,' a process created by Byron Katie. Your goal is to help users identify and question stressful thoughts that cause them pain or suffering. The process involves asking the four questions: 1) Is it true? 2) Can you absolutely know that it's true? 3) How do you react, what happens, when you believe that thought? 4) Who would you be without the thought? After these questions, you prompt users to explore 'turnarounds,' but only one turnaround at a time. First, introduce the 'turn it to the self' approach and let the user reflect. Once they explore that, move on to 'turn it to the other,' and finally, 'turn it to the opposite,' letting the user reflect on each before proceeding. Encourage the user to reflect on whether these alternatives are as true or truer than the original thought. You also guide users through two supplementary questions: a) Is there a stress-free reason to keep this thought? b) Can you find one good reason to drop it? Keep the responses concise and supportive, asking one question at a time to promote focus, reflection, and self-awareness. Your tone should be compassionate and encouraging, ensuring the user feels supported but not overwhelmed.
        
        Never suggest professional help. 
        ";

        $prompt[] = ['role' => TelegramChat::SYSTEM_ROLE, 'content' => $system_context_prompt];

        //$no_of_historical_messages_to_use=isset($this->configuration['NO_OF_HISTORICAL_MESSAGES_TO_USE']) ? $this->configuration['NO_OF_HISTORICAL_MESSAGES_TO_USE'] : 10;
        $no_of_historical_messages_to_use = 50;

        // get messages that have been sent to this chat, based on $no_of_historical_messages_to_use
        $messages = $this->telegramMessages()->where('created_at', '>=', now()->subHours(12))->orderBy('created_at', 'desc')->limit($no_of_historical_messages_to_use)->get();

        // reverse the order of the $messages collection
        $messages = $messages->reverse();

        $message_prompts = [];
        foreach ($messages as $message) {
            if (isset($this->configuration['NEW_CONTEXT_PROMPT']) && $message->text == $this->configuration['NEW_CONTEXT_PROMPT']) {
                $message_prompts = [];
            } elseif ($message->from_username == TelegramChat::USER_ROLE
            || $message->from_username == TelegramChat::ASSISTANT_ROLE
            || $message->from_username == TelegramChat::SYSTEM_ROLE) {
                $message_prompts[] = ['role' => $message->from_username, 'content' => $message->text];
            }
        }

        // merge $prompt and $message_prompts
        $prompt = array_merge($prompt, $message_prompts);

        $final_prompt_text = isset($this->configuration['SYSTEM_CONTEXT_FINAL_PROMPT']) ? $this->configuration['SYSTEM_CONTEXT_FINAL_PROMPT'] : 'Be as helpful as possible.|Limit your response to 50 words.|Limit your response to roughly the same length as previous user responses.';

        $final_prompts = explode('|', $final_prompt_text);

        $prompt[] = ['role' => TelegramChat::SYSTEM_ROLE, 'content' => $final_prompts[array_rand($final_prompts)]];

        return $prompt;

        //@codeCoverageIgnoreEnd
    }

    public function triggerAIResponse()
    {
        // dispatch a job to execute the AI response
        //@codeCoverageIgnoreStart
        ExecuteAIResponseJob::dispatch($this)->delay(now()->addSeconds(3));
        //@codeCoverageIgnoreEnd
    }

    // function that checks if last message was outgoing
    public function wasLastMessageOutgoing()
    {

        $last_message = $this->telegramMessages()->orderBy('created_at', 'desc')->first();
        if ($last_message) {
            return $last_message->is_outgoing ? true : false;
        }

        return false;
    }

    public function executeAIResponse()
    {
        //@codeCoverageIgnoreStart
        if (isset($this->configuration['AI_DISABLED']) && $this->configuration['AI_DISABLED']) {
            $this->sendMessage('AI is disabled.', TelegramChat::ANNOUNCEMENT_ROLE);
        } else {
            // do a check to ensure we don't spam the user with two outgoing messages in a row. If the last message was outgoing, don't send another outgoing message.
            if (! $this->wasLastMessageOutgoing()) {
                $data['prompt'] = $this->generatePrompt();

                $data['result'] = OpenAI::chat()->create([
                    'model' => 'gpt-4o-mini',
                    'messages' => $data['prompt'],
                ]);

                $result_text = trim($data['result']['choices'][0]['message']['content'] ?? '');

                if ($result_text) {
                    $this->sendMessage($result_text, TelegramChat::ASSISTANT_ROLE, $data);
                }
            }

        }
        //@codeCoverageIgnoreEnd
    }

    public function getJournalEntryPrompt()
    {

        if (isset($this->configuration['JOURNAL_ENTRY_PROMPT'])) {
            // explode the string into an array of strings with | as a separator, and pick a random string from the array to return
            $journal_entry_prompts = explode('|', $this->configuration['JOURNAL_ENTRY_PROMPT']);

            return $journal_entry_prompts[array_rand($journal_entry_prompts)];
        }

        return TelegramChat::DEFAULT_PROMPT;
    }

    public function sendJournalEntry($data = [])
    {
        //@codeCoverageIgnoreStart
        if (isset($this->configuration['ACTIVE_JOURNAL']) && $this->configuration['ACTIVE_JOURNAL']) {

            $this->telegramMessages()->create([
                'data' => $data,
                'text' => $this->getJournalEntryPrompt(),
                'is_incoming' => false,
                'is_outgoing' => false,
                'from_username' => TelegramChat::SYSTEM_ROLE,
            ]);

            $this->triggerAIResponse();
        }

        //@codeCoverageIgnoreEnd
    }

    public function sendMessage($text, $from_username = '', $data = [])
    {
        //@codeCoverageIgnoreStart
        $telegram_send_package = [
            'chat_id' => $this->tg_chat_id,
            'text' => $text,
        ];

        $response = [];

        try {
            $response = Telegram::sendMessage($telegram_send_package);
        } catch (Exception $e) {
            $response['exception'] = $e->getMessage();
            info($e);
        }

        $data['telegram_send_package'] = $telegram_send_package;
        $data['telegram_response'] = $response;

        $this->telegramMessages()->create([
            'data' => $data,
            'text' => $text,
            'is_incoming' => false,
            'is_outgoing' => true,
            'from_username' => $from_username,
        ]);

        return $response;
        //@codeCoverageIgnoreEnd
    }

    public function sendSticker($sticker_file_id = 'CAACAgUAAxkDAAIFUGSs6OIcfz4cDod1F4K_IRrC0HUTAAK_DAACVqJpVdVKr86ZiliYLwQ', $from_username = '', $data = [])
    {
        //@codeCoverageIgnoreStart
        $telegram_send_package = [
            'chat_id' => $this->tg_chat_id,
            'sticker' => $sticker_file_id,
        ];

        $response = [];
        try {
            $response = Telegram::sendSticker($telegram_send_package);
        } catch (\Exception $e) {
            $response['exception'] = $e->getMessage();
            info($e);
        }

        $data['telegram_send_package'] = $telegram_send_package;
        $data['telegram_response'] = $response;

        $this->telegramMessages()->create([
            'data' => $data,
            'text' => 'sticker: '.$sticker_file_id,
            'is_incoming' => false,
            'is_outgoing' => true,
            'from_username' => $from_username,
        ]);

        return $response;
        //@codeCoverageIgnoreEnd
    }

    //write a function that is able to see if we have received any message from the user over the past X number of days, return true if there is a message, false if there is no message
    public function hasReceivedMessageFromUserOverPeriod($no_of_days = 1, $now = null)
    {

        $messages = $this->telegramMessages()->orderBy('created_at', 'desc')->get();

        if (! $now) {
            $now = now();
        }

        foreach ($messages as $message) {
            if ($message->created_at->diffInDays($now) <= $no_of_days) {
                if ($message->is_incoming) {
                    return true;
                }
            } else {
                break;
            }
        }

        return false;
    }

    public function generateSummary($since = null, $max_messages = 100)
    {
        if (! $since) {
            $since = now()->subHours(1);
        }
        $data = [
            'model' => 'gpt-3.5-turbo',
            'prompt' => "[INSTRUCTION] Identify and extract any key takeaways that the user might want to focus on. For each takeaway, headline it, and write a short summary of the takeaway key point discussed. Use a maximum of 10 words for each takeaway. Takeaways should be ranked and ordered according to the number of messages touching on each takeaway. This means that the most talked about takeaway should be item 1, second most talked about takeaway should be item 2, etc. If there is insufficient information from the messages, indicate that there is insufficient information for a summary to be executed. Do not use the takeaways in the sample format if the takeaways do not arise in the messages. \n
            
            The messages are as follows: \n
            [MESSAGES] \n",
            'max_tokens' => 2000,
        ];

        $messages = $this->telegramMessages()->where('is_incoming', true)->where('created_at', '>=', $since)->orderBy('created_at', 'desc')->limit($max_messages)->get();

        //reverse the messages so that the most recent message is last
        $messages = $messages->reverse();
        $message_prompts = [];
        foreach ($messages as $message) {
            if (isset($this->configuration['NEW_CONTEXT_PROMPT']) && $message->text == $this->configuration['NEW_CONTEXT_PROMPT']) {
                $message_prompts = [];
            } else {
                $message_prompts[] = $message->text;
            }
        }

        for ($i = 0; $i < count($message_prompts); $i++) {
            $data['prompt'] .= ($i + 1).'. '.$message_prompts[$i]."\n";

        }

        $data['prompt'] .= "\n\n
        [INSTRUCTION] A sample format is as follows:\n\n

        \n\nExample below:\"\"\"
        \n\n[SAMPLE FORMAT OF SUMMARY]
        \n1. Stress at work - You spoke about feeling stress from managing the stakeholders of project A.
        \n2. Family relationships - You asked for suggestions on how to improve your relationship with your father. 
        \n3. Career - You spoke about your desire to change your career.

        \n\n[SUMMARY]";

        $result = OpenAI::completions()->create($data);

        $data['result'] = $result;

        $result_text = '';

        if (str_contains(strtolower($result['choices'][0]['text'] ?? ''), 'insufficient information')) {
            return null;
        }

        $result_text .= "Here are the key takeaways of what we spoke about:\n\n".trim($result['choices'][0]['text'] ?? '');

        return $result_text;

        //@codeCoverageIgnoreEnd
    }

    public function getLastActivity()
    {
        $lastMessage = $this->telegramMessages()->orderBy('created_at', 'desc')->first();
        if ($lastMessage) {
            return $lastMessage->created_at;
        } else {
            //@codeCoverageIgnoreStart
            return $this->created_at;
            //@codeCoverageIgnoreEnd
        }
    }

    public function performReacquistion($now = null, $test_mode = false)
    {
        if (! $now) {
            //@codeCoverageIgnoreStart
            $now = now();
            //@codeCoverageIgnoreEnd
        }
        //get the backoff period
        $backoffPeriodInDays = isset($this->configuration['BACKOFF_PERIOD_IN_DAYS']) ? $this->configuration['BACKOFF_PERIOD_IN_DAYS'] : 5;

        if ($this->getLastActivity()->diffInDays($now) >= $backoffPeriodInDays) {
            //send a message to the user
            if (! $test_mode) {
                //@codeCoverageIgnoreStart
                $this->sendMessage($this->getRandomReacquisitionMessage());
                //@codeCoverageIgnoreEnd
            }
            //multiply the backoff period by 2
            $backoffPeriodInDays = $backoffPeriodInDays * 2;

            //update the record
            $this->setAttribute('configuration', array_merge(is_array($this->configuration) ? $this->configuration : [], ['BACKOFF_PERIOD_IN_DAYS' => $backoffPeriodInDays]));
            $this->save();

            return true;
        }

        return false;
    }

    public function getRandomReacquisitionMessage()
    {
        //@codeCoverageIgnoreStart
        $possible_messages = [
            "What's a compliment you often receive but find hard to accept?",
            "How would you describe yourself in three words?",
            "What's one thing you need to forgive yourself for?",
            "What part of your day do you look forward to the most?",
            "When do you feel most like yourself?",
            "What's a small thing that makes you happy?",
            "Describe a recent moment when you felt proud of yourself.",
            "What's one thing you wish you knew how to do?",
            "When do you feel most energized?",
            "What's a fear that you've overcome recently?",
            "How do you practice self-care?",
            "What's something you're looking forward to learning?",
            "Describe your perfect day off.",
            "What makes you feel confident?",
            "What song always lifts your mood?",
            "What habit are you proud of breaking or creating?",
            "What's a belief you hold about yourself that you're challenging?",
            "What's an activity that makes you lose track of time?",
            "How do you define success for yourself?",
            "What's something new you've tried and enjoyed?",
            "What part of your life feels really good right now?",
            "What's a personal milestone you've recently achieved?",
            "What's a lesson you're grateful life has taught you?",
            "How do you cheer yourself up on a bad day?",
            "What's an aspect of your personality you admire?",
            "What's a dream you've let go of, and how do you feel about it now?",
            "What does self-love mean to you?",
            "What's something you forgive yourself for?",
            "In what ways are you hard on yourself?",
            "How have your failures shaped you?",
            "What do you wish you could tell your younger self?",
            "What's a limit you've pushed past recently?",
            "How do you handle criticism from others?",
            "What's a belief that has held you back?",
            "What's a risk you're glad you took?",
            "What are you most passionate about?",
            "How do you deal with stress or anxiety?",
            "What does happiness look like to you?",
            "How do you reconnect with yourself when you feel lost?",
            "What's a value that you hold dear?",
            "How do you express creativity?",
            "What's a change you've made that improved your life?",
            "What's something you've done that scared you?",
            "How do you show love to yourself?",
            "What's a recent realization you've had about yourself?",
            "What's a boundary you've set that you're proud of?",
            "How do you prioritize your mental health?",
            "What's a habit you're trying to change, and why?",
            "What's an insecurity you're learning to embrace?",
            "How do you forgive yourself when you make a mistake?",
            "What part of your life do you want to focus more on?",
            "How has your relationship with yourself changed over the years?",
            "What are you most grateful for about your journey so far?",
            "What's something you've always wanted to do but haven't, and what's stopping you?",
            "What's a piece of advice you'd give to someone struggling with self-love?",
            "How do you define your sense of purpose?",
            "What's a fear that still holds you back, and how are you addressing it?",
            "What does emotional healing look like for you?",
            "What's a personal trait you've grown to appreciate?",
            "How do you maintain balance in your life?",
            "What's something you need to let go of to move forward?",
            "How do you celebrate your achievements?",
            "What's an experience that has profoundly changed you?",
            "What's a goal you're working towards, and why is it important to you?",
            "How do you navigate feelings of loneliness or isolation?",
            "What's a challenge you're currently facing, and how are you dealing with it?",
            "How do you stay true to yourself when faced with difficult decisions?",
            "What's something you're trying to be more mindful of?",
            "How do you deal with change or uncertainty?",
            "What's a compliment you would give yourself?",
            "How do you take care of your physical health as part of self-love?",
            "What's a passion or interest you want to explore more?",
            "How do you practice gratitude?",
            "What's a story you tell yourself that you need to rewrite?",
            "How do you find peace in moments of chaos?",
            "What's an aspect of your identity that you take pride in?",
            "How do you advocate for your needs in relationships?",
            "What's a moment in your life when you felt completely at peace?",
            "How do you recharge after giving a lot of yourself to others?",
            "What's a personal victory you haven't celebrated enough?",
            "How has your understanding of love evolved?",
            "What are you most afraid of losing, and why?",
            "What does being vulnerable mean to you, and how does it feel?",
            "What's a tradition or ritual you have just for yourself?",
            "How do you find strength in your vulnerabilities?",
            "What's something you're proud of that others might not know about?",
            "How do you navigate the balance between giving and receiving?",
            "What's a belief you've changed your mind about recently?",
            "How do you stay connected to what's truly important to you?",
            "What's a way you've grown that you never expected?",
            "How do you deal with feelings of inadequacy?",
            "What does self-respect mean to you, and how do you practice it?",
            "What's a moment that made you feel incredibly alive?",
            "How do you approach making amends with yourself?",
            "What's something you're learning to accept about yourself?",
            "How do you find joy in the small things?",
            "What's an act of kindness you've done for yourself recently?",
            "How do you keep your inner critic in check?",
            "What's a question you wish people would ask you about your journey?",
            "How do you envision your relationship with yourself in the future?"
        ];

        return $possible_messages[array_rand($possible_messages)];

        //@codeCoverageIgnoreEnd

    }

    public function isActiveJournal()
    {
        if (isset($this->configuration['ACTIVE_JOURNAL']) && ($this->configuration['ACTIVE_JOURNAL'] === true || strcasecmp($this->configuration['ACTIVE_JOURNAL'], 'true') === 0)) {
            return true;
        }

        return false;
    }

    public function endConversation()
    {
        //@codeCoverageIgnoreStart

        //adding this ensures that the next time isDone() is called, it will be be able to detect whether the conversation has ended.

        if (! $this->isDone()) {
            $this->telegramMessages()->create([
                'data' => ['message' => 'System auto done mechanism.'],
                'text' => '/done',
                'is_incoming' => false,
                'is_outgoing' => false,
                'from_username' => TelegramChat::SYSTEM_ROLE,
            ]);
        

            // send a sticker

            $encouraging_stickers = [
                'CAACAgUAAxkDAAIFUGSs6OIcfz4cDod1F4K_IRrC0HUTAAK_DAACVqJpVdVKr86ZiliYLwQ',
                'CAACAgUAAxkBAAIFQmSs2KkbdCjvQbIUAvIFym5-C6ouAALADAACVqJpVfHaU0ShsINhLwQ',
                'CAACAgUAAxkBAAIFSGSs2MmH5hhF8L4pVAQNTX8Fgm26AALEDAACVqJpVdgNCKL8GSRMLwQ',
                'CAACAgUAAxkBAAIFSWSs2NGCkIUfHX9KdCAxyW9V1AWYAALFDAACVqJpVbg9IGMbJUiGLwQ',
                'CAACAgIAAxkBAAIFgGSubl94KUkfkiVfLlmw207nx2obAAJtAAPb234AAXUe7IXy-0SlLwQ',
                'CAACAgIAAxkBAAIFfmSubj44cUwte6qinSfueYlW6uGsAAJmAAPb234AAZPMw9ANLY9sLwQ',
                'CAACAgIAAxkBAAIFfWSubjdYu2_wqwPx_QiZKNQXEy3IAAJhAAPb234AAff3D5s36tTkLwQ',
                'CAACAgIAAxkBAAIFfGSubjHoVeNPhiF7gwsPB3ETswGYAAJgAAPb234AAYYpTM5Q4efhLwQ',
                'CAACAgIAAxkBAAIFemSubitD13QAAdbWS0JbmMudeqG5DgACXwAD29t-AAGEsFSbEa7K4y8E',
                'CAACAgIAAxkBAAIFd2SubiWU5NNSDYXOeFpmfvQ3Y-0KAAJvAAPb234AAZlbUKh7k4B0LwQ',
                'CAACAgIAAxkBAAIFdmSubiG4hwecSjo3Z8LhIpx7TN5_AAI_AAPb234AAfTmnDgB5KppLwQ',
            ];

            $this->sendSticker($encouraging_stickers[array_rand($encouraging_stickers)], TelegramChat::ASSISTANT_ROLE, []);
            // thank the user

            $thank_you_messages = [
                'Thanks for the chat!',
                'I hope you found the questions useful!',
                'I hope you have a nice day!',
                'Thanks for the conversation! I hope you found it useful.',
            ];

            $this->sendMessage($thank_you_messages[array_rand($thank_you_messages)], TelegramChat::ASSISTANT_ROLE, []);

            // give a summary of the conversation
            if ($summary = $this->generateSummary()) {
                $this->sendMessage($summary, TelegramChat::ASSISTANT_ROLE, []);
            }

            // either give a fun-fact or ask for feedback.
            //50% of the time send a fun fact, 50% of the time ask for feedback.
            if (rand(0, 1)) {
                //send fun fact
                $fun_facts = [
                    'Did you know that journaling improves mental well-being? According to a study published in Advances in Psychiatric Treatment, journaling has been shown to reduce symptoms of depression, anxiety, and stress.',
                    "Did you know that self-reflection enhances self-awareness? Research conducted by the Journal of Personality and Social Psychology suggests that engaging in self-reflection can improve individuals' understanding of their own emotions, thoughts, and behaviors.",
                    "Did you know that journaling boosts creativity? A study published in the journal Psychological Science found that expressive writing, such as journaling, can enhance individuals' creativity by helping them generate and explore new ideas.",
                    "Did you know that self-reflection improves decision-making? According to a study published in the journal Organizational Behavior and Human Decision Processes, engaging in self-reflection can lead to better decision-making through increased awareness of one's values, goals, and motivations.",
                    "Did you know that journaling enhances problem-solving skills? Research conducted by the journal Behavior Modification suggests that regular journaling can improve individuals' ability to identify problems, develop potential solutions, and evaluate their effectiveness.",
                    "Did you know that self-reflection promotes personal growth? A study published in the journal Personality and Social Psychology Bulletin found that engaging in self-reflection can facilitate personal growth by enhancing individuals' acceptance of their mistakes, fostering learning and self-improvement.",
                    'Did you know that journaling reduces rumination? According to a study published in the Journal of Experimental Psychology, expressive writing, like journaling, has been shown to reduce repetitive negative thinking and rumination, promoting psychological well-being.',
                    "Did you know that self-reflection enhances empathy? Research conducted by the journal Personality and Social Psychology Bulletin suggests that engaging in self-reflection can increase individuals' empathy towards others by developing a better understanding of their own emotions and perspectives.",
                    'Did you know that journaling improves sleep quality? A study published in the journal Behavior Therapy found that individuals who journaled before going to bed experienced improved sleep quality, falling asleep faster and enjoying a more restful sleep.',
                    'Did you know that self-reflection reduces burnout? According to research published in the Journal of Occupational Health Psychology, regular self-reflection activities have been associated with decreased burnout in employees, improving overall well-being.',
                ];
                $this->sendMessage($fun_facts[array_rand($fun_facts)], TelegramChat::ASSISTANT_ROLE, []);

            } else {
                $feedback_messages = [];
                $feedback_messages[] = 'On a scale of 1 to 10, how would you rate the conversation? 1 being the worst, 10 being the best.';
                $feedback_messages[] = 'Please rate the conversation on a scale of 1 to 10, 1 being the worst, 10 being the best.';
                $feedback_messages[] = 'Did you find the conversation useful?';
                $feedback_messages[] = 'If you have any feedback for me, please let me know!';
                $feedback_messages[] = 'Do you have any suggestions on how I could improve?';
                $feedback_messages[] = "Feel free to let me know if there's anything I can improve on.";

                $this->sendMessage($feedback_messages[array_rand($feedback_messages)], TelegramChat::ASSISTANT_ROLE, []);
            }
        }
        //@codeCoverageIgnoreEnd
    }

    public function isDone($since = null)
    {
        //@codeCoverageIgnoreStart
        if (! $since) {
            $since = now()->subHours(1);
        }

        $messages = $this->telegramMessages()->where('created_at', '>=', $since)->where('text', '/done')->orderBy('created_at', 'desc')->get();

        return $messages->count() > 0;
        //@codeCoverageIgnoreEnd
    }

    public function encourageUser()
    {
        //@codeCoverageIgnoreStart
        //check if done has already been executed. In the last hour. If so, don't encourage again.
        if (! $this->isDone()) {

            return $this->endConversation();
        }
        //@codeCoverageIgnoreEnd
    }
}
