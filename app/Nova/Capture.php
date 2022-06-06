<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Http\Requests\NovaRequest;

class Capture extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Capture::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
   // public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
        'content',
    ];

    public function title(){
        return $this->prefix_with_title();
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make("Priority No")->sortable(),
            BelongsTo::make('Capture')->nullable(),
            Text::make('Name')->sortable()->showOnPreview()->required()->rules('required')->displayUsing(
                function($name){
                    return Str::limit($name, 60);
                }
            ),
            Markdown::make('Content')->alwaysShow(),
            
            Boolean::make('Inbox')->showOnPreview()->sortable()->default(true),
            Boolean::make('Next Action')->showOnPreview()->sortable()->default(false),
            Stack::make('Create/Updated',[
                DateTime::make('Created At')->readonly()->sortable()->exceptOnForms(),
                DateTime::make('Updated At')->readonly()->sortable()->exceptOnForms(),
            ]),

            HasMany::make('Captures'),
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
        return [
            new Lenses\InboxCaptures,
            new Lenses\NextActionCaptures,
        ];
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
            new Actions\RemoveFromInbox,
            new Actions\RemoveFromNextAction,
            new Actions\AddToInbox,
            new Actions\AddToNextAction,
            new Actions\RefreshPriority
        ];
    }
}
