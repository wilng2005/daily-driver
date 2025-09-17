<?php

namespace Tests\Unit\Services;

use App\Models\Capture;
use App\Services\DelayAnalysisService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DelayAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DelayAnalysisService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DelayAnalysisService();

        // Set a fake API key for testing
        config(['openai.api_key' => 'fake-api-key-for-testing']);
    }

    /** @test */
    public function it_has_analyze_method_that_accepts_capture()
    {
        // Arrange
        $capture = Capture::factory()->create([
            'name' => 'Test task',
            'content' => 'Test content'
        ]);

        // Act & Assert - This will test the method signature and basic structure
        // Without a real API key, it will return empty string due to error handling
        $result = $this->service->analyze($capture);
        $this->assertIsString($result);
    }

    /** @test */
    public function it_returns_empty_string_on_api_key_missing()
    {
        // Test with null API key to ensure graceful degradation
        config(['openai.api_key' => null]);

        $capture = Capture::factory()->create([
            'name' => 'Test task',
            'content' => 'Test content'
        ]);

        $result = $this->service->analyze($capture);

        $this->assertEquals('', $result);
    }

    /** @test */
    public function it_handles_empty_capture_name_and_content()
    {
        $capture = Capture::factory()->create([
            'name' => null,
            'content' => null
        ]);

        $result = $this->service->analyze($capture);

        $this->assertIsString($result);
    }

    /** @test */
    public function it_handles_very_long_content()
    {
        $longContent = str_repeat('This is a very long capture content. ', 100);
        $capture = Capture::factory()->create([
            'name' => 'Long task',
            'content' => $longContent
        ]);

        $result = $this->service->analyze($capture);

        $this->assertIsString($result);
    }

    /** @test */
    public function it_handles_special_characters_in_content()
    {
        $capture = Capture::factory()->create([
            'name' => 'Task with símböls & spëcial chars!',
            'content' => 'Content with "quotes", <tags>, and émojis 🎯 & more special chars: @#$%^&*()'
        ]);

        $result = $this->service->analyze($capture);

        $this->assertIsString($result);
    }

    /** @test */
    public function it_does_not_throw_exceptions_on_invalid_input()
    {
        // This tests the error handling - should not throw exceptions
        config(['openai.api_key' => 'invalid-key']);

        $capture = Capture::factory()->create();

        try {
            $result = $this->service->analyze($capture);
            $this->assertIsString($result);
        } catch (Exception $e) {
            $this->fail('Service should not throw exceptions, got: ' . $e->getMessage());
        }
    }

    /** @test */
    public function it_creates_service_instance()
    {
        $this->assertInstanceOf(DelayAnalysisService::class, $this->service);
    }

    /** @test */
    public function service_analyze_method_returns_string()
    {
        $capture = Capture::factory()->create([
            'name' => 'Review quarterly report',
            'content' => 'Need to analyze Q3 financials'
        ]);

        $result = $this->service->analyze($capture);

        $this->assertIsString($result);
    }

    /** @test */
    public function it_works_with_capture_factory()
    {
        // Test that our service works with the capture factory
        $capture = Capture::factory()->create();

        $this->assertInstanceOf(Capture::class, $capture);
        $this->assertIsString($this->service->analyze($capture));
    }

    /** @test */
    public function service_has_protected_generate_prompt_method()
    {
        // Test that the service has the expected structure
        $reflection = new \ReflectionClass(DelayAnalysisService::class);

        $this->assertTrue($reflection->hasMethod('generatePrompt'));
        $this->assertTrue($reflection->getMethod('generatePrompt')->isProtected());
    }
}