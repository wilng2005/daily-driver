<?php

namespace App\Policies;

use App\Models\TelegramMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TelegramMessagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, TelegramMessage $telegramMessage)
    {
        return true;
    }
}
