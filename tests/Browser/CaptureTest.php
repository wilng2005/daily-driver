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
     * A Dusk test example.
     *
     * @return void
     */
    public function test_capture_column_is_working()
    {
        //create 3 captures in the database
        $capture_a = Capture::factory()->create([
            "name"=>"Projects"
        ]);

        $capture_b = Capture::factory()->create([
            "name"=>"Project A1"
        ]);
        
        $capture_c = Capture::factory()->create([
            "name"=>"Task D"
        ]);

        $capture_a->captures()->save($capture_b);
        $capture_b->captures()->save($capture_c);
        $capture_c->save();

        $this->browse(function ($browser) {
            $browser->loginAs(User::find(1))
                ->visit('/nova/resources/captures')
                ->waitForText('Captures')
                ->assertSee('Projects/Project A1')
                ->screenshot('test_capture_column_is_working');
        });

    }
}
