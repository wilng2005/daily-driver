<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Capture;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Carbon;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class DelayUntilDateActionTest extends DuskTestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_requires_a_date_to_delay_capture()
    {
        $user = User::find(1);
        $user->capture_resource_access = 'Self';
        $user->save();
        $capture = Capture::factory()->create(['user_id' => $user->id, 'name' => 'Test Capture', 'inbox' => true, 'next_action' => true]);

        $this->browse(function (Browser $browser) use ($user, $capture) {
            $browser->loginAs($user)
                ->visit('/nova/resources/captures')
                ->waitForText($capture->name)
                ->click('@' . $capture->id . '-checkbox')
                ->waitFor('@action-select')
                ->select('@action-select', 'delay-until-date')
                ->press('Run Action')
                ->waitForText('The Delay Until field is required.')
                ->assertSee('The Delay Until field is required.');
        });
    }

    /** @test */
    public function it_delays_capture_until_valid_date()
    {
        $user = User::find(1);
        $user->capture_resource_access = 'Self';
        $user->save();
        $capture = Capture::factory()->create(['user_id' => $user->id, 'name' => 'Test Capture', 'inbox' => true, 'next_action' => true]);

        $this->browse(function (Browser $browser) use ($user, $capture) {
            $browser->loginAs($user)
                ->visit('/nova/resources/captures')
                ->waitForText($capture->name)
                ->click('@' . $capture->id . '-checkbox')
                ->waitFor('@action-select')
                ->select('@action-select', 'delay-until-date')
                ->type('@delay_until', "12/12/2030")
                ->press('Run Action')
                ->screenshot('delay_until_date_after_run_action')
                ->waitForText('The action was executed successfully.', 10)
                ->assertSee('The action was executed successfully.')
                ;
        });
    }
}
