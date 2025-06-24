<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedirectToCalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_renders_the_redirect_page_for_valid_calcom_url()
    {
        $response = $this->get('/redirect-to-cal?target=https://cal.com/wilng/free-coaching-session');
        $response->assertStatus(200);
        $response->assertSee('Redirecting');
        $response->assertSee('https://cal.com/wilng/free-coaching-session');
    }

    /** @test */
    public function it_rejects_non_calcom_urls()
    {
        $response = $this->get('/redirect-to-cal?target=https://evil.com/phish');
        $response->assertStatus(302); // Expect redirect or rejection
        // Optionally assert redirect location or error message
    }

    /** @test */
    public function it_requires_a_target_parameter()
    {
        $response = $this->get('/redirect-to-cal');
        $response->assertStatus(302); // Expect redirect or rejection
    }
}
