<?php

namespace Tests\Browser;

use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
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
