<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class Summary7Command extends Command
{
    protected string $name = 'summary7';
    protected string $description = 'Generate a summary of your past 7 days';


    /**
     * @codeCoverageIgnore
    **/
    public function handle()
    {

            $this->replyWithMessage([
                'text' => 'This is a seven-day summary of your journaling activity.',
            ]);
    }
}