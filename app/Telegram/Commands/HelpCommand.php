<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'How to use this bot.';


    /**
     * @codeCoverageIgnore
    **/
    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'Hi! This is a GPT-powered chat bot that tries to ask helpful questions to help you think through your problems and emotions. Simply get started by typing /new and respond to the question prompts like you would with any normal conversation.
            
Try to be as reflective and specific about your thoughts and feelings as possible, as this would help you to establish clarity about your own thoughts and feelings.
            
Once you are done, you can type /done to end the conversation and get a summary of your thoughts and feelings.',
        ]);
    }
}