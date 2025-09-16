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
    public function definition(): array
    {
        $suggestions = [
            'Delay 3 days',
            'Delay 1 week',
            'Delay 2 weeks',
            'Delay 1 month',
            'Delay 3 months',
            'Delay 6 months',
            'Delay 1 year',
            'Delay until 2025-10-12',
            'Delay until 2025-11-15',
            'Delay until 2025-12-01',
            'Delay until 2026-01-01',
            'Delay until next Monday',
            'Delay without timeframe',
            null
        ];

        return [
            'name' => $this->faker->sentence(8),
            'content' => $this->faker->paragraphs(3, true),
            'inbox' => $this->faker->boolean(),
            'next_action' => $this->faker->boolean(),
            'ai_delay_suggestion' => $this->faker->randomElement($suggestions),
        ];
    }
}
