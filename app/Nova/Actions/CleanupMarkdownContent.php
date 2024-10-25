<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use OpenAI\Laravel\Facades\OpenAI;

class CleanupMarkdownContent extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $changes = [];

        foreach ($models as $model) {
            $originalContent = $model->content;
            $data['prompt'] = $this->generatePrompt($model->content);

            $data['result'] = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $data['prompt'],
            ]);

            $result_text = trim($data['result']['choices'][0]['message']['content'] ?? '');

            if ($result_text && $result_text !== $originalContent) {
                $model->content = $result_text;
                $model->save();

                $changes[] = [
                    'model_id' => $model->id,
                    'original' => substr($originalContent, 0, 100) . '...',
                    'updated' => substr($result_text, 0, 100) . '...',
                ];
            }
        }

        return Action::message('Cleanup completed. ' . count($changes) . ' models updated.')
            ->withDetails($changes);
    }

    public function generatePrompt($markdown_content)
    {
        return [
            ['role' => 'system', 'content' => 'You are a helpful assistant that helps to clean up and format content into markdown. ONLY RETURN THE MARKDOWN CONTENT, DO NOT INCLUDE ANY OTHER TEXT.'],
            ['role' => 'user', 'content' => 'Here is the markdown content: ' . $markdown_content],
        ];
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
