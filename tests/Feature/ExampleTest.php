<?php

namespace Tests\Feature;

use Tests\TestCase;

final class ExampleTest extends TestCase
{
    public function test_the_nova_login_page_is_available(): void
    {
        $response = $this->get('/nova/login');

        $response->assertStatus(200);
    }
}
