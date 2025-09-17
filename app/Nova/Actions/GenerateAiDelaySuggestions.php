<?php

namespace App\Nova\Actions;

use App\Services\DelayAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateAiDelaySuggestions extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     */
    public $name = 'Generate AI Delay Suggestions';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $delayAnalysisService = app(DelayAnalysisService::class);
        $processedCount = 0;
        $failedCount = 0;

        foreach ($models as $model) {
            try {
                // Generate AI suggestion using the DelayAnalysisService
                $suggestion = $delayAnalysisService->analyze($model);

                if (!empty($suggestion)) {
                    $model->ai_delay_suggestion = $suggestion;
                    $model->save();
                    $processedCount++;
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                // Log error but continue processing other captures
                \Log::error('GenerateAiDelaySuggestions: Failed to process capture', [
                    'capture_id' => $model->id,
                    'error' => $e->getMessage()
                ]);
                $failedCount++;
            }
        }

        // Provide user feedback based on results
        if ($processedCount > 0 && $failedCount === 0) {
            return Action::message("Successfully generated AI delay suggestions for {$processedCount} capture(s).");
        } elseif ($processedCount > 0 && $failedCount > 0) {
            return Action::message("Generated suggestions for {$processedCount} capture(s). {$failedCount} failed - check logs for details.");
        } else {
            return Action::danger("Failed to generate suggestions for all selected captures. Please check logs and try again.");
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}