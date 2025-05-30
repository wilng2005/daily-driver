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
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->json('keywords')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('insight_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insight_id')->constrained('insights')->onDelete('cascade');
            $table->string('header')->nullable();
            $table->text('content_markdown')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('background_color', ['white', 'yellow', 'blue']);
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insight_sections');
        Schema::dropIfExists('insights');
    }
};
