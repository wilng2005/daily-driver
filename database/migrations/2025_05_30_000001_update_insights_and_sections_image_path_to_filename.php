<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('insights', function (Blueprint $table) {
            $table->renameColumn('image_path', 'image_filename');
        });
        Schema::table('insight_sections', function (Blueprint $table) {
            $table->renameColumn('image_path', 'image_filename');
        });
    }

    public function down(): void
    {
        Schema::table('insights', function (Blueprint $table) {
            $table->renameColumn('image_filename', 'image_path');
        });
        Schema::table('insight_sections', function (Blueprint $table) {
            $table->renameColumn('image_filename', 'image_path');
        });
    }
};
