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
     * @return mixed
     */
    public function view(User $user, TelegramChat $telegramChat)
    {
        //@codeCoverageIgnoreStart
        return true;
        //@codeCoverageIgnoreEnd
    }

    /**
     * Determine whether the user can update TelegramChat.
     *
     * @return mixed
     */
    public function update(User $user, TelegramChat $telegramChat)
    {
        //@codeCoverageIgnoreStart
        return true;
        //@codeCoverageIgnoreEnd
    }

    /**
     * Determine whether the user can delete TelegramChat.
     *
     * @return mixed
     */
    public function delete(User $user, TelegramChat $telegramChat)
    {
        //@codeCoverageIgnoreStart
        return true;
        //@codeCoverageIgnoreEnd
    }

    /**
     * Determine whether the user can restore TelegramChat.
     *
     * @return mixed
     */
    public function restore(User $user, TelegramChat $telegramChat)
    {
        //@codeCoverageIgnoreStart
        return true;
        //@codeCoverageIgnoreEnd
    }

    /**
     * Determine whether the user can permanently delete TelegramChat.
     *
     * @return mixed
     */
    public function forceDelete(User $user, TelegramChat $telegramChat)
    {
        //@codeCoverageIgnoreStart
        return true;
        //@codeCoverageIgnoreEnd
    }

    /**
     * Determine whether the user can run actions on TelegramChat.
     *
     * @return mixed
     */
    public function runAction(User $user, TelegramChat $telegramChat)
    {
        //@codeCoverageIgnoreStart
        return true;
        //@codeCoverageIgnoreEnd
    }
}
