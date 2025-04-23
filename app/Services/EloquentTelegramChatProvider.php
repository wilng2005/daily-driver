<?php

namespace App\Services;

use App\Models\TelegramChat;
use Illuminate\Support\Collection;

/**
 * @codeCoverageIgnore
 */
class EloquentTelegramChatProvider implements TelegramChatProvider
{
    public function all(): Collection
    {
        return TelegramChat::all();
    }
}
