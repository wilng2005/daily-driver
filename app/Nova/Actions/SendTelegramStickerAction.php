<?php

namespace App\Nova\Actions;

use App\Models\TelegramChat;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendTelegramStickerAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $telegram_chat) {
            $telegram_chat->sendSticker($fields->sticker_file_id, TelegramChat::ANNOUNCEMENT_ROLE);
        }
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request)
    {
        return [
            Textarea::make('Sticker File Id'),
        ];
    }
}
