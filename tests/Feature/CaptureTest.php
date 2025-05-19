<?php

namespace Tests\Feature;

use App\Models\Capture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class CaptureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_captures_available_in_nav_bar(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/nova/dashboards/main');

        $response->assertSeeText('Captures');
    }

    public function test_create_capture(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/nova/resources/captures/new');

        $response->assertSeeText('Create Capture');
    }



    public function test_capture_prefix_with_title(): void
    {
        $capture_a = new Capture;
        $capture_a->name = 'Projects';

        $capture_b = new Capture;
        $capture_b->name = 'Project A1';
        $capture_b->capture = $capture_a;

        $capture_c = new Capture;
        $capture_c->name = 'Task C';
        $capture_c->capture = $capture_b;

        $this->assertEquals('Projects', $capture_a->prefix_with_title());
        $this->assertEquals('Projects/Project A1', $capture_b->prefix_with_title());
        $this->assertEquals('Projects/Project A1/Task C', $capture_c->prefix_with_title());
    }

    public function test_capture_self_references(): void
    {
        $capture_a = new Capture;
        $capture_a->name = 'Projects';
        $capture_a->save();

        $capture_b = new Capture;
        $capture_b->name = 'Project A1';

        $capture_c = new Capture;
        $capture_c->name = 'Task C';

        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();

        $captures = $capture_a->captures->all();

        $this->assertEquals('Project A1', $captures[0]->name);
        $this->assertEquals('Project A1', $capture_c->capture->name);
    }

    public function test_add_daily_task_to_inbox(): void
    {
        $capture = new Capture;

        $capture->name = 'Daily: Check work email';
        $capture->inbox = false;

        $capture->add_daily_task_to_inbox();
        $this->assertTrue($capture->inbox);

        $capture->name = 'Check work email';
        $capture->inbox = false;

        $capture->add_daily_task_to_inbox();
        $this->assertFalse($capture->inbox);
    }

    public function test_add_weekday_task_to_inbox(): void
    {
        $capture = new Capture;

        $capture->name = 'Weekday: Check work email';
        $capture->inbox = false;

        $capture->add_weekday_task_to_inbox();
        $this->assertTrue($capture->inbox);

        $capture->name = 'Check work email';
        $capture->inbox = false;

        $capture->add_weekday_task_to_inbox();
        $this->assertFalse($capture->inbox);
    }

    public function test_add_scheduled_task_to_inbox(): void
    {
        $capture = new Capture;

        $capture->name = '2022-05-30: Check work email';
        $capture->inbox = false;

        $capture->add_scheduled_task_to_inbox(Carbon::createFromFormat('Y-m-d', '2022-05-30'));
        $this->assertTrue($capture->inbox);

        $capture = new Capture;

        $capture->name = '2022-05-31: Check work email';
        $capture->inbox = false;

        $capture->add_scheduled_task_to_inbox(Carbon::createFromFormat('Y-m-d', '2022-05-30'));
        $this->assertFalse($capture->inbox);

        $capture = new Capture;

        $capture->name = '2022-05-14: Check work email';
        $capture->inbox = false;

        $capture->add_scheduled_task_to_inbox(Carbon::createFromFormat('Y-m-d', '2022-05-30'));
        $this->assertTrue($capture->inbox);

    }

    public function test_generate_delayed_name_prefix(): void
    {
        //make a static method call to generate_delayed_name_prefix of capture model
        $str = Capture::generate_delayed_name_prefix('2023-10-30 Hello Worlda', '1 week', Carbon::create(2023, 10, 30, 12));
        $this->assertEquals('2023-11-06 Hello Worlda', $str);
        $str = Capture::generate_delayed_name_prefix('2023-10-30 Hello World', '2 weeks', Carbon::create(2023, 10, 30, 12));
        $this->assertEquals('2023-11-13 Hello World', $str);
        $str = Capture::generate_delayed_name_prefix(null, '1 month', Carbon::create(2023, 10, 30, 12));
        $this->assertEquals('2023-11-30', $str);
    }
}
