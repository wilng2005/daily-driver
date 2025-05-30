<?php

namespace Database\Factories;

use App\Models\Insight;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsightFactory extends Factory
{
    protected $model = Insight::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'keywords' => $this->faker->words(5),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
