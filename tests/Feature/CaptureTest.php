<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class CaptureTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_captures_available_in_nav_bar()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
             ->get('/nova/dashboards/main');

        $response->assertSeeText("Captures");
    }

    public function test_create_capture(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)
             ->get('/nova/resources/captures/new');

        $response->assertSeeText("Create Capture");
    }
}
