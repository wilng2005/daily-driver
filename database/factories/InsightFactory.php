<?php

namespace Database\Factories;

use App\Models\Insight;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InsightFactory extends Factory
{
    protected $model = Insight::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();
        $imageKeys = array_keys(config('image_dimensions'));
        $imageFilename = $imageKeys ? $this->faker->randomElement($imageKeys) : null;
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'image_filename' => $imageFilename,
            'description' => $this->faker->paragraph(),
            'keywords' => $this->faker->words(5),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
