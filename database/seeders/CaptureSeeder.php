<?php

namespace Database\Seeders;

use App\Models\Capture;
use Illuminate\Database\Seeder;

class CaptureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Capture::factory()
            ->count(30)
            ->create();
    }
}
