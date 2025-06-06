<?php

namespace Database\Factories;

use App\Models\InsightSection;
use App\Models\Insight;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsightSectionFactory extends Factory
{
    protected $model = InsightSection::class;

    public function definition(): array
    {
        $backgroundColors = ['white', 'yellow', 'blue', null];
        $imageKeys = array_keys(config('image_dimensions'));
        return [
            'insight_id' => Insight::factory(),
            'header' => $this->faker->sentence(),
            'content_markdown' => $this->faker->paragraphs(2, true),
            'image_filename' => $imageKeys ? $this->faker->randomElement($imageKeys) : null,
            'background_color' => $this->faker->randomElement($backgroundColors),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
