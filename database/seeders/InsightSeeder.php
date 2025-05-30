<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insight;
use App\Models\InsightSection;
use Illuminate\Support\Facades\DB;

class InsightSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InsightSection::truncate();
        Insight::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create 10 insights, each with 5 sections
        Insight::factory()->count(10)->create()->each(function ($insight) {
            InsightSection::factory()->count(5)->create([
                'insight_id' => $insight->id,
            ]);
        });
    }
}
