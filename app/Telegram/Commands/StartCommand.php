<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start a new conversation.';


    /**
     * @codeCoverageIgnore
    **/
    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'Hi! What would you like to talk about today?',
        ]);
    }
}