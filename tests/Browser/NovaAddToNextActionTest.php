<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NovaAddToNextActionTest extends DuskTestCase
{
    use WithFaker, DatabaseMigrations;

    /**
     * Test that the 'Add to Next Action' Nova action fails due to the current bug.
     *
     * @return void
     */
    public function test_add_to_next_action_fails()
    {
        // Seed a user and a capture record
        $user = \App\Models\User::factory()->create([
            'email' => 'dusk-nova@example.com',
            'password' => bcrypt('password'),
            'capture_resource_access' => 'All', // Ensure full access in test
        ]);

        $capture = \App\Models\Capture::factory()->create([
            'user_id' => $user->id,
            'name' => 'Dusk Test Capture',
        ]);

        $this->browse(function (Browser $browser) use ($user, $capture) {
            $browser->loginAs($user)
                ->visit('/nova/resources/captures')
                ->waitForText('Dusk Test Capture', 10)
                // Take a screenshot for selector inspection
                ->screenshot('nova-capture-index')
                // Select the checkbox using Nova's dusk attribute for this capture
                ->click('@' . $capture->id . '-checkbox')
                // Wait for the actions dropdown to appear, then select and run the action
                ->waitFor('@action-select', 5)
                ->select('@action-select', 'add-to-next-action')
                ->waitForText('Run Action', 5)
                ->press('Run Action')
                // Wait for Nova success notification (adjust text if your Nova uses different wording)
                ->waitForText('Action executed successfully', 5)
                // Wait for the green checkmark to appear in the UI
                ->waitFor('.text-green-500', 5)
                ->assertPresent('.text-green-500');

        // Refresh the capture and assert the action succeeded
        $capture->refresh();
        $this->assertTrue((bool)$capture->next_action, 'Capture should have next_action=true after AddToNextAction');
        });
    }
}
