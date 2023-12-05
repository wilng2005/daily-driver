<?php

namespace App\Models;

use App\Jobs\EncourageUserJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['data'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function extract_and_store_chat_and_message_details()
    {
        //@codeCoverageIgnoreStart

        $telegram_chat = null;

        if (isset($this->data['message']['chat'])) {
            $telegram_chat = TelegramChat::updateOrCreate(
                ['tg_chat_id' => $this->data['message']['chat']['id']],
                ['data' => $this->data['message']['chat']]
            );

            $telegram_chat->telegramMessages()->create([
                'data' => $this->data['message'],
                'text' => $this->data['message']['text'] ?? '',
                'is_incoming' => true,
                'is_outgoing' => false,
                'from_username' => TelegramChat::USER_ROLE,
            ]);
        }

        EncourageUserJob::dispatch($telegram_chat)->delay(now()->addMinutes(15));

        return $telegram_chat;
        //@codeCoverageIgnoreEnd
    }
}
