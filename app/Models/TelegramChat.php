<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramChat extends Model
{
    use HasFactory;

    //the tg_chat_id value is used by the  bot api to identify the chat.
    protected $fillable = ['data','tg_chat_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function telegramMessages(): HasMany
    {
        //@codeCoverageIgnoreStart
        return $this->hasMany(TelegramMessage::class);
        //@codeCoverageIgnoreEnd
    }
}
