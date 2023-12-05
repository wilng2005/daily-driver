<?php

namespace Tests\Browser;

use App\Models\Capture;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;

class CaptureTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * This is a very basic test.
     *
     * @return void
     */
    public function test_capture_column_is_working()
    {

        $user = User::find(1);
        $user->capture_resource_access = 'Self';
        $user->save();

        //create 3 captures in the database
        $capture_a = Capture::factory()->create([
            'name' => 'Projects',
            'user_id' => 1,
        ]);

        $capture_b = Capture::factory()->create([
            'name' => 'Project A1',
            'user_id' => 1,
        ]);

        $capture_c = Capture::factory()->create([
            'name' => 'Task D',
            'user_id' => 1,
        ]);

        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/nova/resources/captures')
                ->waitForText('Captures')
                ->assertSee('Projects/Project A1')
                ->screenshot('test_capture_column_is_working');
        });

    }

    /**
     * Tests for Capture Access None
     *  - User cannot see their own captures on index
     *  - User cannot see other people's captures on index
     *  - User cannot see their own capture on Next Action
     *  - User cannot see other people's capture on Next Action
     *  - User cannot see their own capture on Inbox
     *  - User cannot see other people's capture on Inbox
     **/
    public function test_capture_access_none()
    {
        $user = User::find(1);
        $user->capture_resource_access = 'None';
        $user->save();

        //create 3 captures in the database
        $capture_a = Capture::factory()->create([
            'name' => 'Projects',
            'user_id' => 1,
        ]);

        $capture_b = Capture::factory()->create([
            'name' => 'Project A1',
            'user_id' => 1,
            'inbox' => true,
            'next_action' => true,
        ]);

        $capture_c = Capture::factory()->create([
            'name' => 'Task D',
            'user_id' => 1,
        ]);

        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();

        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //create 3 captures in the database
        $capture_c = Capture::factory()->create([
            'name' => 'UA Projects',
            'user_id' => $user_a->id,
        ]);

        $capture_d = Capture::factory()->create([
            'name' => 'UA Project A1',
            'user_id' => $user_a->id,
            'inbox' => true,
            'next_action' => true,
        ]);

        $capture_e = Capture::factory()->create([
            'name' => 'UA Task D',
            'user_id' => $user_a->id,
        ]);

        $this->browse(function ($browser) use ($user, $capture_b, $capture_d) {

            // assert that the user can see their own captures on index
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/')
                ->assertSee('403')
                ->assertDontSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/'.$capture_b->id)
                ->assertSee('403')
                ->assertDontSee('Project A1');

            // assert that the user cannot see other people's captures on index
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/')
                ->assertSee('403')
                ->assertDontSee('UA Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/'.$capture_d->id)
                ->assertSee('403')
                ->assertDontSee('UA Project A1');

            // assert that the user cannot see other people's captures on inbox
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/inbox-captures')
                ->assertDontSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/inbox-captures')
                ->assertDontSee('UA Project A1');

            // assert that the user cannot see other people's captures on inbox
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/next-action-captures')
                ->assertDontSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/next-action-captures')
                ->assertDontSee('UA Project A1');
        });
    }

    /**
     * Tests for Capture Access Self
     *  - User should see their own captures on index
     *  - User cannot see other people's captures on index
     *  - User should see their own capture on Next Action
     *  - User cannot see other people's capture on Next Action
     *  - User should see their own capture on Inbox
     *  - User cannot see other people's capture on Inbox
     **/
    public function test_capture_access_self()
    {
        $user = User::find(1);
        $user->capture_resource_access = 'Self';
        $user->save();

        //create 3 captures in the database
        $capture_a = Capture::factory()->create([
            'name' => 'Projects',
            'user_id' => 1,
        ]);

        $capture_b = Capture::factory()->create([
            'name' => 'Project A1',
            'user_id' => 1,
            'inbox' => true,
            'next_action' => true,
        ]);

        $capture_c = Capture::factory()->create([
            'name' => 'Task D',
            'user_id' => 1,
        ]);

        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();

        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //create 3 captures in the database
        $capture_c = Capture::factory()->create([
            'name' => 'UA Projects',
            'user_id' => $user_a->id,
        ]);

        $capture_d = Capture::factory()->create([
            'name' => 'UA Project A1',
            'user_id' => $user_a->id,
            'inbox' => true,
            'next_action' => true,
        ]);

        $capture_e = Capture::factory()->create([
            'name' => 'UA Task D',
            'user_id' => $user_a->id,
        ]);

        $this->browse(function ($browser) use ($user, $capture_b, $capture_d) {

            // assert that the user can see their own captures on index
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/')
                ->waitForText('Captures')
                ->waitForText('Project A1')
                ->assertSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/'.$capture_b->id)
                ->waitForText('Capture Details')
                ->waitForText('Project A1')
                ->assertSee('Project A1');

            // assert that the user cannot see other people's captures on index
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/')
                ->waitForText('Captures')
                ->assertDontSee('UA Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/'.$capture_d->id)
                ->assertSee('403')
                ->assertDontSee('UA Project A1');

            // assert that the user cannot see other people's captures on inbox
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/inbox-captures')
                ->waitForText('Inbox Captures')
                ->assertSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/inbox-captures')
                ->waitForText('Inbox Captures')
                ->assertDontSee('UA Project A1');

            // assert that the user cannot see other people's captures on inbox
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/next-action-captures')
                ->waitForText('Next Action Captures')
                ->assertSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/next-action-captures')
                ->waitForText('Next Action Captures')
                ->assertDontSee('UA Project A1');
        });
    }

    /**
     * Tests for Capture Access all
     *  - User should see their own captures on index
     *  - User should see other people's captures on index
     *  - User should see their own capture on Next Action
     *  - User cannot see other people's capture on Next Action
     *  - User should see their own capture on Inbox
     *  - User cannot see other people's capture on Inbox
     **/
    public function test_capture_access_all()
    {
        $user = User::find(1);
        $user->capture_resource_access = 'All';
        $user->save();

        //create 3 captures in the database
        $capture_a = Capture::factory()->create([
            'name' => 'Projects',
            'user_id' => 1,
        ]);

        $capture_b = Capture::factory()->create([
            'name' => 'Project A1',
            'user_id' => 1,
            'inbox' => true,
            'next_action' => true,
        ]);

        $capture_c = Capture::factory()->create([
            'name' => 'Task D',
            'user_id' => 1,
        ]);

        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();

        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //create 3 captures in the database
        $capture_c = Capture::factory()->create([
            'name' => 'UA Projects',
            'user_id' => $user_a->id,
        ]);

        $capture_d = Capture::factory()->create([
            'name' => 'UA Project A1',
            'user_id' => $user_a->id,
            'inbox' => true,
            'next_action' => true,
        ]);

        $capture_e = Capture::factory()->create([
            'name' => 'UA Task D',
            'user_id' => $user_a->id,
        ]);

        $this->browse(function ($browser) use ($user, $capture_b, $capture_d) {

            // assert that the user can see their own captures on index
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/')
                ->waitForText('Captures')
                ->waitForText('Project A1')
                ->assertSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/'.$capture_b->id)
                ->waitForText('Capture Details')
                ->assertSee('Project A1');

            // assert that the user can see other people's captures on index
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/')
                ->waitForText('Captures')
                ->waitForText('UA Project A1')
                ->assertSee('UA Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/'.$capture_d->id)
                ->waitForText('Capture Details')
                ->assertSee('UA Project A1');

            // assert that the user cannot see other people's captures on inbox
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/inbox-captures')
                ->waitForText('Inbox Captures')
                ->assertSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/inbox-captures')
                ->waitForText('Inbox Captures')
                ->assertDontSee('UA Project A1');

            // assert that the user cannot see other people's captures on inbox
            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/next-action-captures')
                ->waitForText('Next Action Captures')
                ->assertSee('Project A1');

            $browser->loginAs($user)
                ->visit('/nova/resources/captures/lens/next-action-captures')
                ->waitForText('Next Action Captures')
                ->assertDontSee('UA Project A1');
        });
    }
}
