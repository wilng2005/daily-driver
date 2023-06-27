<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'How to use this bot.';


    /**
     * @codeCoverageIgnore
    **/
    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'Hi! This is a GPT-powered chat bot that asks helpful questions to help you reflect your problems and emotions. Simply get started by typing /new and respond to the question prompts like you would with any normal conversation.
            
Try to be as reflective and specific as possible, as this would help you to establish clarity about your own thoughts and feelings.
            
Once you are done, you can type /done to end the conversation and get a summary of your thoughts and feelings.',
        ]);
    }
}