<?php

namespace Tests\Feature;

use App\Models\Capture;
use App\Models\User;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;



class CaptureTest extends TestCase
{
    use RefreshDatabase;
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

    public function test_capture_prefix_with_title()
    {
        $capture_a = new Capture;
        $capture_a->name= "Projects";

        $capture_b = new Capture;
        $capture_b->name= "Project A1";
        $capture_b->capture=$capture_a;

        $capture_c = new Capture;
        $capture_c->name= "Task C";
        $capture_c->capture=$capture_b;
        
        $this->assertEquals("Projects", $capture_a->prefix_with_title());
        $this->assertEquals("Projects/Project A1", $capture_b->prefix_with_title());
        $this->assertEquals("Projects/Project A1/Task C", $capture_c->prefix_with_title());
    }

    public function test_capture_self_references()
    {
        $capture_a = new Capture;
        $capture_a->name= "Projects";
        $capture_a->save();

        $capture_b = new Capture;
        $capture_b->name= "Project A1";
        

        $capture_c = new Capture;
        $capture_c->name= "Task C";
        
        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();
        
        
        $captures =$capture_a->captures->all();

        $this->assertEquals("Project A1", $captures[0]->name);
        $this->assertEquals("Project A1", $capture_c->capture->name);
    }
}
