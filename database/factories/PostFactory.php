<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'content' => $this->faker->paragraphs(4, true),
            'status' => 'published',
            'source' => 'manual',
            'ai_prompt' => null,
            'published_at' => now(),
        ];
    }
}
