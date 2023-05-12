<?php

namespace App\Policies;

use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TelegramChatPolicy
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
    
    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TelegramChat  $telegramChat
     * @return mixed
     */
    public function view(User $user, TelegramChat $telegramChat)
    {
        return true;
    }


    /**
     * Determine whether the user can run actions on TelegramChat.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TelegramChat  $telegramChat
     * @return mixed
     */
    public function runAction(User $user, TelegramChat $telegramChat)
    {
        return true;
    }

    
}
