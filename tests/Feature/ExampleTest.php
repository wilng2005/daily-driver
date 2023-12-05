<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_nova_login_page_is_available()
    {
        $response = $this->get('/nova/login');

        $response->assertStatus(200);
    }
}
