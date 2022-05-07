<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_redirect_response()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_the_nova_login_page_is_available()
    {
        $response = $this->get('/nova/login');

        $response->assertStatus(200);
    }
}
