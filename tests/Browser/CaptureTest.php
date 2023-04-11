<?php

namespace Tests\Browser;

use App\Models\Capture;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
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

        $user=User::find(1);
        $user->capture_resource_access="Self";
        $user->save();

        //create 3 captures in the database
        $capture_a = Capture::factory()->create([
            "name"=>"Projects",
            "user_id"=>1,
        ]);

        $capture_b = Capture::factory()->create([
            "name"=>"Project A1",
            "user_id"=>1,
        ]);
        
        $capture_c = Capture::factory()->create([
            "name"=>"Task D",
            "user_id"=>1,
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
    
    /**
     * Tests for Capture Access Self
     *  - User should see their own captures on index
     *  - User cannot see other people's captures on index
     *  - User should see their own capture on Next Action
     *  - User cannot see other people's capture on Next Action
     *  - User should see their own capture on Inbox
     *  - User cannot see other people's capture on Inbox
     **/ 
    
     /**
     * Tests for Capture Access all
     *  - User should see their own captures on index
     *  - User should see other people's captures on index
     *  - User should see their own capture on Next Action
     *  - User cannot see other people's capture on Next Action
     *  - User should see their own capture on Inbox
     *  - User cannot see other people's capture on Inbox
     **/ 

     /**
      * Tests for Capture Access All with User Access All
      */


}
