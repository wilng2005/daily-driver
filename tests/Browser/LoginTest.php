<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
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
}
