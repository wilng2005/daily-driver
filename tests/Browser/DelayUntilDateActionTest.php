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
        $capture = Capture::factory()->create(['user_id' => $user->id, 'name' => 'Test Capture', 'inbox' => true, 'next_action' => true]);

        $this->browse(function (Browser $browser) use ($user, $capture) {
            $browser->loginAs($user)
                ->visit('/nova/resources/captures')
                ->waitForText($capture->name)
                ->check('input[type="checkbox"][value="'.$capture->id.'"]')
                ->press('@actions-dropdown')
                ->click('@action-delay-until-date')
                ->press('Run Action')
                ->waitForText('The delay until field is required') // Adjust to match Nova's actual validation message
                ;
        });
    }

    /** @test */
    public function it_delays_capture_until_valid_date()
    {
        $user = User::find(1);
        $capture = Capture::factory()->create(['user_id' => $user->id, 'name' => 'Test Capture', 'inbox' => true, 'next_action' => true]);
        $futureDate = Carbon::now()->addDays(3)->toDateString();

        $this->browse(function (Browser $browser) use ($user, $capture, $futureDate) {
            $browser->loginAs($user)
                ->visit('/nova/resources/captures')
                ->waitForText($capture->name)
                ->check('input[type="checkbox"][value="'.$capture->id.'"]')
                ->press('@actions-dropdown')
                ->click('@action-delay-until-date')
                ->type('delay_until', $futureDate)
                ->press('Run Action')
                ->waitForText('Action executed successfully') // Adjust as needed
                ;
        });
    }
}
