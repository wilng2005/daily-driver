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
    public function it_returns_sections_ordered_by_order_field()
    {
        $insight = Insight::factory()->create();
        $sectionA = InsightSection::factory()->create([
            'insight_id' => $insight->id,
            'header' => 'Section A',
            'order' => 2,
        ]);
        $sectionB = InsightSection::factory()->create([
            'insight_id' => $insight->id,
            'header' => 'Section B',
            'order' => 1,
        ]);
        $sectionC = InsightSection::factory()->create([
            'insight_id' => $insight->id,
            'header' => 'Section C',
            'order' => 3,
        ]);

        $response = $this->get("/insights/{$insight->slug}");
        $response->assertStatus(200);
        $response->assertViewHas('sections', function ($sections) {
            $orders = collect($sections)->pluck('order')->all();
            return $orders === [1, 2, 3];
        });
    }

    /** @test */
    public function it_orders_sections_by_id_when_order_is_the_same()
    {
        $insight = Insight::factory()->create();
        $section1 = InsightSection::factory()->create([
            'insight_id' => $insight->id,
            'header' => 'Section 1',
            'order' => 1,
        ]);
        $section2 = InsightSection::factory()->create([
            'insight_id' => $insight->id,
            'header' => 'Section 2',
            'order' => 1,
        ]);
        $section3 = InsightSection::factory()->create([
            'insight_id' => $insight->id,
            'header' => 'Section 3',
            'order' => 1,
        ]);

        // Shuffle creation order
        $ids = [$section1->id, $section2->id, $section3->id];
        sort($ids);

        $response = $this->get("/insights/{$insight->slug}");
        $response->assertStatus(200);
        $response->assertViewHas('sections', function ($sections) use ($ids) {
            $actualIds = collect($sections)->pluck('id')->all();
            return $actualIds === $ids;
        });
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
