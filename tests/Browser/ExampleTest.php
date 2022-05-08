<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/nova/login')
                    ->assertSee('Log In');
        });
    }

    public function test_basic_example()
    {

        $this->browse(function ($first, $second) {
            $first->loginAs(User::find(1))
              ->visit('/home')
              ->waitForText('Message');
          
        $user = User::factory()->create([
            'email' => 'taylor@laravel.com',
        ]);
 
        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }
}
