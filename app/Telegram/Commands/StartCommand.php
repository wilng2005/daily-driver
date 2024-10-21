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
    public function handle(): void
    {
        $this->replyWithMessage([
            'text' => 'This chat bot is designed to help you to challenge and break free from limiting beliefs in your life. Just type /new to get started, and answer the prompts just like a natural conversation.

When you are ready to wrap up, simply type /done.

Let us challenge those limits together and uncover new paths forward!',
        ]);
    }
}
