<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramMessage extends Model
{
    use HasFactory;

    protected $fillable = ['data','telegram_chat_id','text','is_incoming','is_outgoing','from_username'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];


    public function telegramChat(): BelongsTo
    {
         //@codeCoverageIgnoreStart
        return $this->belongsTo(TelegramChat::class);
        //@codeCoverageIgnoreEnd
    }
    
    public static function incomingDailyMessageCount($date=null){
        if(!$date){
            $date=now();
        }
        
        return TelegramMessage::whereDate('created_at',$date->startOfDay()->toDateString())->where('is_incoming',true)->count();
    }
}
