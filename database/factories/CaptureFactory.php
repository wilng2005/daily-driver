<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Capture>
 */
class CaptureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(8),
            'content' => $this->faker->paragraphs(3,true),
            'inbox'=> $this->faker->boolean(),
            'next_action'=> $this->faker->boolean(),
        ];
    }
}
