<?php

namespace Tests\Feature;

use App\Models\Capture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoDailyScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $capture_a = new Capture;

        $capture_a->name = 'Daily: Check work email';
        $capture_a->inbox = false;

        $capture_a->save();

        $capture_b = new Capture;

        $capture_b->name = '2021-05-30: Check work email';
        $capture_b->inbox = false;

        $capture_b->save();

        $this->artisan('schedule:daily')->assertExitCode(0);

        $capture_a->refresh();
        $capture_b->refresh();

        $this->assertTrue((bool) $capture_a->inbox);

        $this->assertTrue((bool) $capture_b->inbox);
    }
}
