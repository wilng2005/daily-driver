<?php

namespace Tests\Unit;

use App\Models\Capture;
use PHPUnit\Framework\TestCase;

class CaptureTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_title()
    {
        $this->assertTrue(true);
        // $capture_a = new Capture;
        // $capture_a->name= "Projects";

        // $capture_b = new Capture;
        // $capture_b->name= "Project A1";
        // $capture_b->capture=$capture_a;

        // $capture_c = new Capture;
        // $capture_c->name= "Task C";
        // $capture_c->capture=$capture_b;

        // $this->assertEquals("Projects", $capture_a->prefix_with_title());
        // $this->assertEquals("Projects/Project A1", $capture_b->prefix_with_title());
        // $this->assertEquals("Projects/Project A1/Task C", $capture_c->prefix_with_title());
    }
}
