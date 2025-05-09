<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TechLeadsPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    // public function it_displays_published_articles_in_insights_and_stories_section()
    // {
    //     // Arrange: Use the PostSeeder to seed the database
    //     $this->artisan('db:seed', ['--class' => 'PostSeeder']);

    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //             ->assertPresent('[data-testid="insights-and-stories-section"]')
    //             ->assertSeeIn('[data-testid="insights-and-stories-section"]', 'Insights and Stories');

    //         // Debug: Dump the text inside the targeted section
    //         $sectionText = $browser->text('[data-testid="insights-and-stories-section"]');

    //         $this->assertStringContainsString('Insights and Stories', $sectionText, '[DEBUG] insights-and-stories-section should contain heading text.');

    //         // Carousel: Only a few articles are visible at a time. Test by advancing the slider.
    //         $totalArticles = 10;
    //         $visibleAtOnce = 3; // Carousel shows 3 articles per slide
    //         $slides = (int) ceil($totalArticles / $visibleAtOnce);

    //         $browser->visit('/')
    //             ->waitFor('[data-testid="insights-and-stories-section"]', 5)
    //             ->assertSeeIn('[data-testid="insights-and-stories-section"]', 'Insights and Stories')
    //             ->assertSeeIn('[data-testid="insights-and-stories-section"]', 'Sample Article #1')
    //             ->assertSeeLink('Read More');

    //         // Optionally, still check that draft articles are not visible
    //         $browser->assertDontSee('Draft Article #1')
    //                 ->assertDontSee('Draft Article #2');
    //         // Do NOT assert that only Sample Articles are present; allow extra articles for manual testing.

    //         // Check that every third post has an <img> (decorative asset)
    //         // This assumes .details-each wraps each article and <img> appears for every 3rd post
    //         $elements = $browser->elements('.section-slider .details-each');
    //         $imgCount = 0;
    //         foreach ($elements as $index => $element) {
    //             if ((($index + 1) % 3) === 0) {
    //                 $img = $browser->element(
    //                     ".section-slider .details-each:nth-child(" . ($index + 1) . ") img.icon-image"
    //                 );
    //                 if ($img) {
    //                     $imgCount++;
    //                 }
    //             }
    //         }
    //         $this->assertGreaterThanOrEqual(3, $imgCount, 'Decorative asset images should appear for every third post.');


    //     });
    // }
}
