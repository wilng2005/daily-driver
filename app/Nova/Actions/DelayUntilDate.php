<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ActionRequest;
use Carbon\Carbon;

class DelayUntilDate extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Delay Until Date';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // DEBUG: Dump fields and request for troubleshooting
        \Log::info('DelayUntilDate fields:', (array) $fields);
        \Log::info('DelayUntilDate request:', request()->all());
        return Action::danger(json_encode([
            'fields' => (array) $fields,
            'request' => request()->all(),
        ]));

        // --- Original logic below ---
        $delayUntil = $fields->delay_until;

        if (empty($delayUntil)) {
            return Action::danger('Please select a date to delay until.');
        }

        try {
            $date = Carbon::parse($delayUntil);
            
            if ($date->isPast()) {
                return Action::danger('The selected date must be in the future.');
            }

            foreach ($models as $model) {
                $model->name = $model::generate_delayed_name_prefix($model->name, $date);
                $model->inbox = false;
                $model->next_action = false;
                $model->save();
            }

            return Action::message('Successfully delayed ' . $models->count() . ' item(s) until ' . $date->format('M j, Y'));
        } catch (\Exception $e) {
            return Action::danger('Invalid date format. Please use YYYY-MM-DD.');
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
            Date::make('Delay Until', 'delay_until')
                ->rules('required', 'after_or_equal:today')
                ->help('Select a future date to delay this item until')
                ->displayUsing(fn () => now()->format('Y-m-d')),
        ];
    }

    /**
     * Get the validation rules for the action.
     *
     * @param  \Laravel\Nova\Http\Requests\ActionRequest  $request
     * @return array
     */
    public function rules(ActionRequest $request)
    {
        return [
            'delay_until' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * Get the validation messages for the action.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'delay_until.required' => 'Please select a date to delay until.',
            'delay_until.after_or_equal' => 'The selected date must be in the future.',
        ];
    }
}
