<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class SummaryCommand extends Command
{
    protected string $name = 'summary';
    protected string $description = 'Generate a summary of your journaling activity.';


    /**
     * @codeCoverageIgnore
    **/
    public function handle()
    {
            $this->replyWithMessage([
                'text' => 'Generate a summary of your journaling activity.',
            ]);
    }
}