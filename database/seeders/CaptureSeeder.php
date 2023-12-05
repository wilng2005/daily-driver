<?php

namespace Database\Seeders;

use App\Models\Capture;
use Illuminate\Database\Seeder;

class CaptureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Capture::factory()
            ->count(30)
            ->create();
    }
}
