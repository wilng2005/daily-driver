<?php

namespace Tests\Feature;

use App\Models\Insight;
use App\Models\InsightSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class InsightTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_insight_with_sections()
    {
        $insight = Insight::factory()
            ->has(InsightSection::factory()->count(3), 'sections')
            ->create();

        $this->assertDatabaseHas('insights', [
            'id' => $insight->id,
            'title' => $insight->title,
        ]);
        $this->assertCount(3, $insight->sections);
    }

    /** @test */
    public function it_can_update_an_insight_and_sections()
    {
        $insight = Insight::factory()
            ->has(InsightSection::factory()->count(2), 'sections')
            ->create();

        $insight->update([
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'keywords' => ['updated', 'keywords'],
        ]);

        $section = $insight->sections()->first();
        $section->update([
            'header' => 'Updated Header',
            'background_color' => 'yellow',
        ]);

        $this->assertDatabaseHas('insights', [
            'id' => $insight->id,
            'title' => 'Updated Title',
        ]);
        $this->assertDatabaseHas('insight_sections', [
            'id' => $section->id,
            'header' => 'Updated Header',
            'background_color' => 'yellow',
        ]);
    }

    /** @test */
    public function it_can_delete_an_insight_and_cascade_sections()
    {
        $insight = Insight::factory()->has(InsightSection::factory()->count(2), 'sections')->create();
        $sectionIds = $insight->sections->pluck('id');

        $insight->delete();

        $this->assertDatabaseMissing('insights', ['id' => $insight->id]);
        foreach ($sectionIds as $id) {
            $this->assertDatabaseMissing('insight_sections', ['id' => $id]);
        }
    }

    /** @test */
    public function it_only_returns_published_insights()
    {
        Carbon::setTestNow(Carbon::parse('2025-05-30 08:00:00'));

        $published = Insight::factory()->create(['published_at' => Carbon::now()->subDay()]);
        $future = Insight::factory()->create(['published_at' => Carbon::now()->addDay()]);
        $draft = Insight::factory()->create(['published_at' => null]);

        $visible = Insight::published()->get();

        $this->assertTrue($visible->contains($published));
        $this->assertFalse($visible->contains($future));
        $this->assertFalse($visible->contains($draft));
    }
}
