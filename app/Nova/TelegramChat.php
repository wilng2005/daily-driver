<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TelegramChat extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Journaling';

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TelegramChat>
     */
    public static $model = \App\Models\TelegramChat::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->readonly(),
            Text::make('Chat Name', function () {

                $chat_name=isset($this->data['username'])?$this->data['username']:'';
                $chat_name.=isset($this->data['first_name'])?' '.$this->data['first_name']:'';
                $chat_name.=isset($this->data['last_name'])?' '.$this->data['last_name']:'';
                $chat_name.=isset($this->data['type'])?' '.$this->data['type']:'';
                $chat_name=trim($chat_name);
                return $chat_name;
            }),
            Text::make('Message Count', function () {
                return $this->getNoOfMessagesSentOverPeriod(1);
            }),
            KeyValue::make('Configuration')->rules('json'),
            Number::make('Telegram Chat ID','tg_chat_id')->readonly(),
            Code::make('Data')->json()->readonly(),
            Stack::make('Create/Updated',[
                DateTime::make('Created At')->readonly()->sortable()->exceptOnForms(),
                DateTime::make('Updated At')->readonly()->sortable()->exceptOnForms(),
            ]),
            HasMany::make('Telegram Messages'),
            Markdown::make('System Documentation', function(){
                return "
**Message Count**  
Total number of messages sent over the last 1 day.
";
            })->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new Actions\SendTelegramMessageAction,
            new Actions\SendJournalEntryAction,
        ];
    }
}
