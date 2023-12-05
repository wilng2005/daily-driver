<?php

namespace App\Telegram\Commands;

use App\Models\TelegramUpdate;
use Telegram\Bot\Commands\Command;

class DoneCommand extends Command
{
    protected string $name = 'done';

    protected string $description = 'End the conversation.';

    /**
     * @codeCoverageIgnore
     **/
    public function handle(): void
    {
        $update = $this->getUpdate();
        $telegram_update = TelegramUpdate::create([
            'data' => $update,
        ]);

        $telegram_chat = $telegram_update->extract_and_store_chat_and_message_details();
        $telegram_chat->endConversation();
    }
}
