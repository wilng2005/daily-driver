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
        $backgroundColors = ['white', 'yellow', 'blue'];
        $images = glob(public_path('images/*.{jpg,jpeg,png,gif,webp}'), GLOB_BRACE);
        return [
            'insight_id' => Insight::factory(),
            'header' => $this->faker->sentence(),
            'content_markdown' => $this->faker->paragraphs(2, true),
            'image_filename' => $images ? basename($this->faker->randomElement($images)) : null,
            'background_color' => $this->faker->randomElement($backgroundColors),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
