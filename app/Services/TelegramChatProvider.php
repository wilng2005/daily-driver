<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface TelegramChatProvider
{
    public function all(): Collection;
}
