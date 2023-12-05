<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class DelayCapture extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //
        foreach ($models as $model) {
            if ($fields->duration) {
                $model->name = $model::generate_delayed_name_prefix($model->name, $fields->duration);
            }
            $model->inbox = false;
            $model->next_action = false;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Duration')->options([
                '3 days' => '3 days',
                '1 week' => '1 week',
                '2 weeks' => '2 weeks',
                '1 month' => '1 month',
                '3 months' => '3 months',
                '6 months' => '6 months',
                '1 year' => '1 year',
            ]),
        ];
    }
}
