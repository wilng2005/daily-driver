<?php

namespace Tests\Browser;

use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class UserTest extends DuskTestCase
{
    use DatabaseMigrations;

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
    public function test_user_access_all(){
        //create a user with access none
        
        $user = User::factory()->create([
            'user_resource_access' => 'All',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {
            //try to access the dashboard
            //assert that the user can access the user resource on the left nav
            $browser->loginAs($user)
                ->visit('/nova/dashboards/main')
                ->waitForText('Things To Do')
                ->waitForText('Users')
                ->assertSee('Things To Do')                
                ->assertSee('Users');

            //try to access the index
            //assert that the user can see another users' name
            //assert that the user can access the index
            $browser->loginAs($user)
                ->visit('/nova/resources/users')
                ->waitForText($user_a->name)
                ->waitForText('Users')
                ->assertDontSee('403')
                ->assertSee($user_a->name)
                ->assertSee('Users');

            //try to access a separate user object
            //assert that the separate user object can be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->waitForText('User Details:')
                ->assertDontSee('403')   
                ->assertSee('User Details:');
        });
     }

     /**
      * Tests for Capture Access All with User Access All
      *  - User should see other other people's captures when viewing the user object
      *  - If capture access is self/none, then the user should see the user object without the captures below it.
      */

      public function test_user_access_all_with_capture_access_all(){
        //create a user with access none
        
        $user = User::factory()->create([
            'user_resource_access' => 'All',
            'capture_resource_access' => 'All',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {

            //try to access a separate user object
            //assert that the separate user object can be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->waitForText('User Details:')
                ->waitForText('Create Capture')
                ->assertDontSee('403')                
                ->assertSee('User Details:')
                ->assertSee('Create Capture');
        });
     }

    /**
      * Tests for User Access All with Capture Access self
      */

      public function test_user_access_all_with_capture_access_self(){
        //create a user with access none
        
        $user = User::factory()->create([
            'user_resource_access' => 'All',
            'capture_resource_access' => 'Self',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {

            //try to access a separate user object
            //assert that the separate user object can be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->assertDontSee('403')                
                ->assertSee('User Details:')
                ->assertDontSee('Create Capture');

            //try to access a own user object
            //assert that the separate user object can be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user->id)
                ->waitForText('User Details:')
                ->waitForText('Create Capture')
                ->assertDontSee('403')                
                ->assertSee('User Details:')
                ->assertSee('Create Capture');
        });
     }

    /**
      * Tests for User Access All with Capture Access None
      */

    public function test_user_access_all_with_capture_access_none(){
        $user = User::factory()->create([
            'user_resource_access' => 'All',
            'capture_resource_access' => 'None',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {

            //try to access a separate user object
            //assert that the separate user object can be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->waitForText('User Details:')
                ->assertDontSee('403')                
                ->assertSee('User Details:')
                ->assertDontSee('Create Capture');

            //try to access a own user object
            //assert that the separate user object can be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user->id)
                ->waitForText('User Details:')
                ->assertDontSee('403')                
                ->assertSee('User Details:')
                ->assertDontSee('Create Capture');
        });


    }



     /**
     * Tests for User Access self
     *  - User should see their own user object on index
     *  - User should not see or be able to access other other people's user object on index
     **/ 

     public function test_user_access_self(){
        //create a user with access none
        
        $user = User::factory()->create([
            'user_resource_access' => 'Self',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {
            //try to access the dashboard
            //assert that the user can access the user resource on the left nav
            $browser->loginAs($user)
                ->visit('/nova/dashboards/main')
                ->waitForText('Things To Do')
                ->waitForText('Users')
                ->assertSee('Things To Do')                
                ->assertSee('Users');

            //try to access the index
            //assert that the user cannot see another users' name
            //assert that the user can access the index
            $browser->loginAs($user)
                ->visit('/nova/resources/users')
                ->assertDontSee('403')
                ->assertDontSee($user_a->name)
                ->assertSee('Users');

            //try to access a separate user object
            //assert that the separate user object cannot be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->assertSee('403')                
                ->assertDontSee('User Details:');
                
        });
     }

     /**
     * Tests for User Access None
     *  - User should not be able to access the index
     **/ 
     public function test_user_access_none(){
        //create a user with access none
        
        $user = User::factory()->create([
            'user_resource_access' => 'None',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {
            //try to access the dashboard
            //assert that the user cannot access the user resource on the left nav
            $browser->loginAs($user)
                ->visit('/nova/dashboards/main')
                ->waitForText('Things To Do')
                ->assertSee('Things To Do')                
                ->assertDontSee('Users');

            //try to access the index
            //assert that the user cannot access the index
            $browser->loginAs($user)
                ->visit('/nova/resources/users')
                ->assertSee('403')                
                ->assertDontSee('Users');

        //try to access a separate user object
        //assert that the separate user object cannot be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->assertSee('403')                
                ->assertDontSee('User Details:');
        });
     }

    

    /**
     * Tests for User Access blank
     *  - User should not be able to access the index
     **/ 
    public function test_user_access_blank(){
        //create a user with access blank
        
        $user = User::factory()->create([
            'user_resource_access' => '',
        ]);
        
        //create an alternate user record
        $user_a = User::factory()->create([
            'user_resource_access' => 'All',
        ]);

        //login as that user

        $this->browse(function ($browser) use ($user, $user_a) {
            //try to access the dashboard
            //assert that the user cannot access the user resource on the left nav
            $browser->loginAs($user)
                ->visit('/nova/dashboards/main')
                ->waitForText('Things To Do')
                ->assertSee('Things To Do')                
                ->assertDontSee('Users');

            //try to access the index
            //assert that the user cannot access the index
            $browser->loginAs($user)
                ->visit('/nova/resources/users')
                ->assertSee('403')                
                ->assertDontSee('Users');

        //try to access a separate user object
        //assert that the separate user object cannot be accessed
            $browser->loginAs($user)
                ->visit('/nova/resources/users/'.$user_a->id)
                ->assertSee('403')                
                ->assertDontSee('User Details:');
        });
     }

}
