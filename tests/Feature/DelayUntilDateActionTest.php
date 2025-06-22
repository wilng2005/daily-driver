<?php

namespace Tests\Feature;

use App\Models\Capture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DelayUntilDateActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Freeze time for consistent testing
        $this->travelTo(now()->startOfDay());
    }

    /** @test */
    public function it_prepends_date_to_capture_name()
    {
        $user = User::factory()->create(['capture_resource_access' => 'All']);
        $capture = Capture::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test task',
            'inbox' => true,
            'next_action' => true
        ]);

        $futureDate = now()->addWeek();

        $response = $this->actingAs($user)
            ->post('/nova-api/captures/action?action=delay-until-date', [
                'resources' => $capture->id,
                'fields' => [
                    'delay_until' => $futureDate->format('Y-m-d')
                ]
            ]);

        $response->assertStatus(200);
        $capture->refresh();
        
        $this->assertStringStartsWith($futureDate->format('Y-m-d'), $capture->name);
        $this->assertStringEndsWith('Test task', $capture->name);
        $this->assertFalse($capture->inbox);
        $this->assertFalse($capture->next_action);
    }

    /** @test */
    public function it_handles_existing_date_prefix()
    {
        $user = User::factory()->create(['capture_resource_access' => 'All']);
        $existingDate = now()->subDays(2);
        $newDate = now()->addWeek();
        
        $capture = Capture::factory()->create([
            'user_id' => $user->id,
            'name' => $existingDate->format('Y-m-d') . ' Old task',
            'inbox' => true,
            'next_action' => true
        ]);

        $response = $this->actingAs($user)
            ->postJson('/nova-api/captures/action?action=delay-until-date', [
                'resources' => $capture->id,
                'fields' => [
                    'delay_until' => $newDate->format('Y-m-d')
                ]
            ]);

        $response->assertStatus(200);
        $capture->refresh();
        
        $this->assertStringStartsWith($newDate->format('Y-m-d'), $capture->name);
        $this->assertStringEndsWith('Old task', $capture->name);
        $this->assertStringNotContainsString($existingDate->format('Y-m-d'), $capture->name);
    }

    /** @test */
    public function it_validates_future_date()
    {
        $user = User::factory()->create(['capture_resource_access' => 'All']);
        $capture = Capture::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->postJson('/nova-api/captures/action?action=delay-until-date', [
                'resources' => $capture->id,
                'fields' => [
                    'delay_until' => now()->subDay()->format('Y-m-d')
                ]
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['delay_until']);
    }

    /** @test */
    public function it_requires_date()
    {
        $user = User::factory()->create(['capture_resource_access' => 'All']);
        $capture = Capture::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->postJson('/nova-api/captures/action?action=delay-until-date', [
                'resources' => $capture->id,
                'fields' => []
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['delay_until']);
    }

    /** @test */
    public function it_handles_multiple_captures()
    {
        $user = User::factory()->create(['capture_resource_access' => 'All']);
        $captures = Capture::factory()->count(3)->create(['user_id' => $user->id]);
        $futureDate = now()->addWeek();

        $response = $this->actingAs($user)
            ->postJson('/nova-api/captures/action?action=delay-until-date', [
                'resources' => 'all',
                'fields' => (object)[
                    'delay_until' => $futureDate->format('Y-m-d')
                ]
            ]);

        $response->assertStatus(200);
        
        foreach ($captures as $capture) {
            $capture->refresh();
            $this->assertStringStartsWith($futureDate->format('Y-m-d'), $capture->name);
            $this->assertFalse($capture->inbox);
            $this->assertFalse($capture->next_action);
        }
    }
}
