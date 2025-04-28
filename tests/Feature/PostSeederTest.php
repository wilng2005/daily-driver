<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;

class PostSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_seeds_the_featured_article_post()
    {
        $this->seed(\Database\Seeders\PostSeeder::class);

        $article = Post::where('slug', 'why-its-so-hard-to-find-a-good-career-or-performance-coach')->first();
        $this->assertNotNull($article, 'The featured article was not seeded.');
        $this->assertStringContainsString('Why It’s So Hard to Find a Good Career or Performance Coach', $article->title);
        $this->assertStringContainsString('In the business world, even luminaries like Google’s Eric Schmidt', $article->content);
    }
}
