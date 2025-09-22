<?php

namespace Tests\Feature\Nova\Actions;

use App\Models\Capture;
use App\Nova\Actions\GenerateAiDelaySuggestions;
use App\Services\DelayAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\ActionFields;
use Mockery;
use Tests\TestCase;

class GenerateAiDelaySuggestionsTest extends TestCase
{
    use RefreshDatabase;

    protected GenerateAiDelaySuggestions $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GenerateAiDelaySuggestions();
    }

    /** @test */
    public function it_has_correct_action_name()
    {
        $this->assertEquals('Generate AI Delay Suggestions', $this->action->name);
    }

    /** @test */
    public function it_returns_empty_fields_array()
    {
        $request = Mockery::mock(\Laravel\Nova\Http\Requests\NovaRequest::class);
        $fields = $this->action->fields($request);

        $this->assertIsArray($fields);
        $this->assertEmpty($fields);
    }

    /** @test */
    public function it_successfully_processes_single_capture()
    {
        // Arrange
        $capture = Capture::factory()->create([
            'name' => 'Test task',
            'content' => 'Test content',
            'ai_delay_suggestion' => null
        ]);

        $models = new Collection([$capture]);
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock the DelayAnalysisService
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->once()
            ->with($capture)
            ->andReturn('Delay 3 days');

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        // Act
        $result = $this->action->handle($actionFields, $models);

        // Assert
        $this->assertNotNull($result);
        $capture->refresh();
        $this->assertEquals('Delay 3 days', $capture->ai_delay_suggestion);
    }

    /** @test */
    public function it_successfully_processes_multiple_captures()
    {
        // Arrange
        $captures = Capture::factory()->count(3)->create([
            'ai_delay_suggestion' => null
        ]);

        $models = new Collection($captures->all());
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock the DelayAnalysisService
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->times(3)
            ->andReturn('Delay 1 week', 'Delay 2 days', 'Delay until Monday');

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        // Act
        $result = $this->action->handle($actionFields, $models);

        // Assert
        $this->assertNotNull($result);
        foreach ($captures as $capture) {
            $capture->refresh();
            $this->assertNotNull($capture->ai_delay_suggestion);
        }
    }

    /** @test */
    public function it_handles_empty_suggestions_gracefully()
    {
        // Arrange
        $capture = Capture::factory()->create([
            'ai_delay_suggestion' => null
        ]);

        $models = new Collection([$capture]);
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock service to return empty string
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->once()
            ->with($capture)
            ->andReturn('');

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        // Act
        $result = $this->action->handle($actionFields, $models);

        // Assert - empty suggestion should not overwrite existing value
        $this->assertNotNull($result);
        $capture->refresh();
        $this->assertNull($capture->ai_delay_suggestion);
    }

    /** @test */
    public function it_handles_service_exceptions_gracefully()
    {
        // Arrange
        $capture = Capture::factory()->create([
            'ai_delay_suggestion' => null
        ]);

        $models = new Collection([$capture]);
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock service to throw exception
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->once()
            ->andThrow(new \Exception('Service error'));

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        Log::shouldReceive('error')
            ->once()
            ->with('GenerateAiDelaySuggestions: Failed to process capture', Mockery::type('array'));

        // Act
        $result = $this->action->handle($actionFields, $models);

        // Assert - should handle exception gracefully
        $this->assertNotNull($result);
        $capture->refresh();
        $this->assertNull($capture->ai_delay_suggestion);
    }

    /** @test */
    public function it_does_not_overwrite_existing_suggestions_with_empty_results()
    {
        // Arrange
        $capture = Capture::factory()->create([
            'ai_delay_suggestion' => 'Existing suggestion'
        ]);

        $models = new Collection([$capture]);
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock service to return empty string
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->once()
            ->andReturn('');

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        // Act
        $this->action->handle($actionFields, $models);

        // Assert - existing suggestion should remain unchanged
        $capture->refresh();
        $this->assertEquals('Existing suggestion', $capture->ai_delay_suggestion);
    }

    /** @test */
    public function it_updates_existing_suggestions_with_new_results()
    {
        // Arrange
        $capture = Capture::factory()->create([
            'ai_delay_suggestion' => 'Old suggestion'
        ]);

        $models = new Collection([$capture]);
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock service to return new suggestion
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->once()
            ->andReturn('New suggestion');

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        // Act
        $this->action->handle($actionFields, $models);

        // Assert
        $capture->refresh();
        $this->assertEquals('New suggestion', $capture->ai_delay_suggestion);
    }

    /** @test */
    public function it_respects_single_user_constraint()
    {
        // All captures in the system have user_id = 1 due to the technical debt constraint
        $capture = Capture::factory()->create([
            'user_id' => 1,
            'ai_delay_suggestion' => null
        ]);

        $this->assertEquals(1, $capture->user_id);

        // The action should work normally as all captures belong to user_id = 1
        $models = new Collection([$capture]);
        $actionFields = new ActionFields(collect([]), collect([]));

        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->once()
            ->andReturn('Delay 2 weeks');

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        $result = $this->action->handle($actionFields, $models);

        $this->assertNotNull($result);
    }

    /** @test */
    public function it_continues_processing_after_individual_failures()
    {
        // Arrange
        $captures = Capture::factory()->count(3)->create([
            'ai_delay_suggestion' => null
        ]);

        $models = new Collection($captures->all());
        $actionFields = new ActionFields(collect([]), collect([]));

        // Mock service: success, exception, success
        $mockService = Mockery::mock(DelayAnalysisService::class);
        $mockService->shouldReceive('analyze')
            ->times(3)
            ->andReturnUsing(function ($capture) use ($captures) {
                if ($capture->id === $captures[1]->id) {
                    throw new \Exception('Middle capture error');
                }
                return 'Delay 1 week';
            });

        $this->app->bind(DelayAnalysisService::class, function () use ($mockService) {
            return $mockService;
        });

        Log::shouldReceive('error')
            ->once()
            ->with('GenerateAiDelaySuggestions: Failed to process capture', Mockery::type('array'));

        // Act
        $result = $this->action->handle($actionFields, $models);

        // Assert - should continue processing despite individual failures
        $this->assertNotNull($result);

        $captures[0]->refresh();
        $captures[1]->refresh();
        $captures[2]->refresh();

        $this->assertEquals('Delay 1 week', $captures[0]->ai_delay_suggestion);
        $this->assertNull($captures[1]->ai_delay_suggestion);
        $this->assertEquals('Delay 1 week', $captures[2]->ai_delay_suggestion);
    }
}