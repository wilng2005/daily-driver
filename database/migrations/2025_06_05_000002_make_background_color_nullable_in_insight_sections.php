<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('insight_sections', function (Blueprint $table) {
            $table->enum('background_color', ['white', 'yellow', 'blue'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insight_sections', function (Blueprint $table) {
            $table->enum('background_color', ['white', 'yellow', 'blue'])->nullable(false)->change();
        });
    }
};
