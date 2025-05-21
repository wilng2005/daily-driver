<?php

namespace Tests\Feature;

use Tests\TestCase;

class OpenAiRandomNumberTest extends TestCase
{

    /** @test */
    public function it_returns_a_random_number_between_1_and_12_by_default()
    {
        $response = $this->getJson('/api/open-ai/random-number');
        $response->assertStatus(200)
            ->assertJsonStructure(['random'])
            ->assertJson(fn ($json) =>
                $json->where('random', fn ($value) => $value >= 1 && $value <= 12)
            );
    }

    /** @test */
    public function it_returns_a_random_number_within_custom_range()
    {
        $response = $this->getJson('/api/open-ai/random-number?min=5&max=10');
        $response->assertStatus(200)
            ->assertJsonStructure(['random'])
            ->assertJson(fn ($json) =>
                $json->where('random', fn ($value) => $value >= 5 && $value <= 10)
            );
    }

    /** @test */
    public function it_returns_the_same_number_when_min_equals_max()
    {
        $response = $this->getJson('/api/open-ai/random-number?min=7&max=7');
        $response->assertStatus(200)
            ->assertJson(['random' => 7]);
    }

    /** @test */
    public function it_returns_validation_error_for_non_integer_input()
    {
        $response = $this->getJson('/api/open-ai/random-number?min=foo&max=bar');
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['min', 'max']);
    }

    /** @test */
    public function it_returns_validation_error_when_min_greater_than_max()
    {
        $response = $this->getJson('/api/open-ai/random-number?min=10&max=5');
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['min']);
    }

    /** @test */
    public function it_is_publicly_accessible()
    {
        $response = $this->getJson('/api/open-ai/random-number');
        $response->assertStatus(200);
    }
}
