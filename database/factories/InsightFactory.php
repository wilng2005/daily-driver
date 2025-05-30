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
        // Mimic how InsightSection chooses images from public/images
        $images = glob(public_path('images/*.{jpg,jpeg,png,gif,webp}'), GLOB_BRACE);
        $imagePath = $images ? 'images/' . basename($this->faker->randomElement($images)) : null;
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'image_path' => $imagePath,
            'description' => $this->faker->paragraph(),
            'keywords' => $this->faker->words(5),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
