<?php

namespace Tests\Unit;

use App\Models\Insight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope_returns_only_published_insights()
    {
        $published = Insight::factory()->create(['published_at' => now()->subDay()]);
        $scheduled = Insight::factory()->create(['published_at' => now()->addDay()]);
        $draft = Insight::factory()->create(['published_at' => null]);

        $results = Insight::published()->get();

        $this->assertTrue($results->contains($published));
        $this->assertFalse($results->contains($scheduled));
        $this->assertFalse($results->contains($draft));
    }

    public function test_slug_is_generated_from_title_on_save()
    {
        $insight = Insight::create([
            'title' => 'My Unique Insight Title',
            // no slug
        ]);

        $this->assertEquals('my-unique-insight-title', $insight->slug);
    }

    public function test_slug_is_not_overwritten_if_already_set()
    {
        $insight = Insight::create([
            'title' => 'Another Title',
            'slug' => 'custom-slug',
        ]);

        $this->assertEquals('custom-slug', $insight->slug);
    }
}

