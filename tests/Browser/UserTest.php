<?php

namespace Tests\Browser;

use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class UserTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoginPageIsAvailable()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/nova/login')
                    ->assertSee('Welcome Back!');
        });
    }

    /**
     * Tests for User Access all
     *  - User should see their own user object on index
     *  - User should see other other people's user object on index
     **/ 

     /**
      * Tests for Capture Access All with User Access All
      *  - User should see other other people's captures when viewing the user object
      *  - If capture access is self/none, then the user should see the user object without the captures below it.
      */

     /**
     * Tests for User Access self
     *  - User should see their own user object on index
     *  - User should not see or be able to access other other people's user object on index
     **/ 

     /**
     * Tests for User Access None
     *  - User should not be able to access the index
     **/ 

}
