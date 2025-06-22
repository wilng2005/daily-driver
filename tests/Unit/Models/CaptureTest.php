<?php

namespace Tests\Unit\Models;

use App\Models\Capture;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaptureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(Carbon::parse('2025-06-21 00:00:00'));
    }

    /** @test */
    public function it_generates_delayed_name_prefix_with_date_object()
    {
        $capture = new Capture();
        $futureDate = now()->addWeek();
        
        $result = $capture::generate_delayed_name_prefix('Test task', $futureDate);
        
        $this->assertEquals('2025-06-28 Test task', $result);
    }

    /** @test */
    public function it_generates_delayed_name_prefix_with_date_string()
    {
        $capture = new Capture();
        
        $result = $capture::generate_delayed_name_prefix('Test task', '2025-07-01');
        
        $this->assertEquals('2025-07-01 Test task', $result);
    }

    /** @test */
    public function it_replaces_existing_date_prefix()
    {
        $capture = new Capture();
        $newDate = now()->addWeek();
        
        $result = $capture::generate_delayed_name_prefix(
            '2025-05-01 Old task',
            $newDate
        );
        
        $this->assertEquals('2025-06-28 Old task', $result);
    }

    /** @test */
    public function it_handles_empty_name()
    {
        $capture = new Capture();
        $futureDate = now()->addWeek();
        
        $result = $capture::generate_delayed_name_prefix('', $futureDate);
        
        $this->assertEquals('2025-06-28', $result);
    }
}
