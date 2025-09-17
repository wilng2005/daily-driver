<?php

namespace App\Services;

use App\Models\Capture;
use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class DelayAnalysisService
{
    /**
     * Analyze a capture and generate a delay suggestion.
     *
     * @param Capture $capture
     * @return string Empty string on failure, suggestion text on success
     */
    public function analyze(Capture $capture): string
    {
        try {
            Log::info('DelayAnalysisService: Starting analysis for capture', [
                'capture_id' => $capture->id,
                'capture_name' => $capture->name
            ]);

            $prompt = $this->generatePrompt($capture);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $prompt,
                'max_tokens' => 100,
                'temperature' => 0.7,
            ]);

            $suggestion = trim($response['choices'][0]['message']['content'] ?? '');

            Log::info('DelayAnalysisService: Analysis completed successfully', [
                'capture_id' => $capture->id,
                'suggestion' => $suggestion
            ]);

            return $suggestion;

        } catch (Exception $e) {
            Log::error('DelayAnalysisService: Analysis failed', [
                'capture_id' => $capture->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return empty string for graceful degradation
            return '';
        }
    }

    /**
     * Generate the AI prompt for delay analysis.
     *
     * @param Capture $capture
     * @return array
     */
    protected function generatePrompt(Capture $capture): array
    {
        $systemPrompt = 'You are a task management assistant that suggests appropriate delay timeframes for tasks. Analyze the provided task and suggest one of these delay formats:

- Relative duration: "Delay 3 days", "Delay 1 week", "Delay 2 weeks", "Delay 1 month"
- Specific date: "Delay until December 1st", "Delay until next Monday", "Delay until next Friday"
- Indefinite: "Delay without timeframe" (for someday/maybe items)

Consider:
- Urgency and importance of the task
- Time-sensitive elements (deadlines, events, seasons)
- Task complexity and preparation time needed
- Dependencies on other tasks or people

Respond with ONLY the delay suggestion text. Do not include explanations or additional text.';

        $userPrompt = sprintf(
            'Task: %s

Content: %s',
            $capture->name ?? '',
            $capture->content ?? ''
        );

        return [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt]
        ];
    }
}