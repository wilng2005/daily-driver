<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;

class DelayUntilDate extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // Implementation will be added after TDD cycle
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request)
    {
        return [
            Date::make('Delay Until', 'delay_until')
                ->rules('required', 'date', 'after_or_equal:today'),
        ];
    }

    /**
     * The URI key for the action (for Nova API endpoint).
     */
    public function uriKey()
    {
        return 'delay-until-date';
    }
}
