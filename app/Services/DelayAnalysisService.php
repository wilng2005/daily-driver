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

            // @codeCoverageIgnoreStart
            $suggestion = trim($response['choices'][0]['message']['content'] ?? '');

            Log::info('DelayAnalysisService: Analysis completed successfully', [
                'capture_id' => $capture->id,
                'suggestion' => $suggestion
            ]);

            return $suggestion;
            // @codeCoverageIgnoreEnd

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
        $currentDate = now()->format('l, F j, Y'); // e.g., "Thursday, September 19, 2024"
        $currentTime = now()->format('g:i A T'); // e.g., "2:30 PM PDT"

        $systemPrompt = "You are a task management assistant that suggests appropriate delay timeframes for tasks.

CURRENT CONTEXT:
- Today is: {$currentDate}
- Current time: {$currentTime}

Analyze the provided task and suggest one of these delay formats:

- Relative duration: \"Delay 3 days\", \"Delay 1 week\", \"Delay 2 weeks\", \"Delay 1 month\"
- Specific date: \"Delay until December 1st\", \"Delay until next Monday\", \"Delay until next Friday\"
- Indefinite: \"Delay without timeframe\" (for someday/maybe items)

Consider:
- Urgency and importance of the task
- Time-sensitive elements (deadlines, events, seasons)
- Task complexity and preparation time needed
- Dependencies on other tasks or people
- Current date context for accurate relative suggestions

When suggesting specific dates, use the current date context to calculate accurate timeframes.
Examples: If today is Thursday and you suggest \"next Monday\", that would be in 4 days.

Respond with ONLY the delay suggestion text. Do not include explanations or additional text.";

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