<?php

namespace App\Telegram\Commands;

use App\Models\TelegramUpdate;
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
        $update=$this->getUpdate();
        $telegram_update=TelegramUpdate::create([
            'data'=> $update
        ]);

        $telegram_chat=$telegram_update->extract_and_store_chat_and_message_details();
        $telegram_chat->generate_summary();
    }
}