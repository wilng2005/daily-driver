<?php

namespace App\Nova\Lenses;

use Illuminate\Database\Eloquent\Builder;
use App\Nova\Metrics\IncomingTelegramMessagesPerDay;
use App\Nova\Metrics\ThingsToDo;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class InboxCaptures extends Lens
{
    public static $showPollingToggle = true;

    /**
     * Get the query builder / paginator for the lens.
     *
     * @return mixed
     */
    public static function query(LensRequest $request, Builder $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->where('inbox', true)
                ->where('user_id', $request->user()->id)
                ->orderBy('priority_no')
        ));
    }

    /**
     * Get the fields available to the lens.
     */
    public function fields(NovaRequest $request): array
    {
        return [

            Text::make('Name')->sortable()->displayUsing(
                function ($name) {
                    return Str::limit($name, 60);
                }
            ),
            Text::make('Content')->displayUsing(
                function ($content) {
                    return Str::limit($content, 30);
                }
            ),
            Number::make('Priority No')->sortable(),
        ];
    }

    /**
     * Get the cards available on the lens.
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new ThingsToDo,
            new IncomingTelegramMessagesPerDay,
        ];
    }

    /**
     * Get the filters available for the lens.
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     */
    public function actions(NovaRequest $request): array
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     */
    public function uriKey(): string
    {
        return 'inbox-captures';
    }
}
