<?php

namespace Tests\Unit;

use App\Models\Insight;
use App\Models\InsightSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightSectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_insight_section_belongs_to_insight()
    {
        $insight = Insight::factory()->create();
        $section = InsightSection::factory()->create(['insight_id' => $insight->id]);

        $this->assertTrue($section->insight->is($insight));
    }
}
