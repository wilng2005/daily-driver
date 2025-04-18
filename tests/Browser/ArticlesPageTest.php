<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ArticlesPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_displays_published_articles_on_the_articles_index()
    {
        // Arrange: create some published and unpublished posts
        Post::factory()->create([
            'title' => 'Visible Article',
            'slug' => 'visible-article',
            'content' => 'This should appear.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        Post::factory()->create([
            'title' => 'Draft Article',
            'slug' => 'draft-article',
            'content' => 'This should NOT appear.',
            'status' => 'draft',
            'published_at' => null,
        ]);

        // Act & Assert
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->assertSee('Articles')
                ->screenshot('articles-page-debug') // Save a screenshot for debugging
                ->assertSee('Visible Article')
                ->assertDontSee('Draft Article');
        });
    }

    /** @test */
    public function it_links_to_the_post_detail_page()
    {
        $post = Post::factory()->create([
            'title' => 'Linked Article',
            'slug' => 'linked-article',
            'content' => 'Details page test.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->browse(function (Browser $browser) use ($post) {
            $browser->visit('/articles')
                ->clickLink('Linked Article')
                ->assertPathIs('/post/' . $post->slug)
                ->assertSee('Details page test.');
        });
    }
}
