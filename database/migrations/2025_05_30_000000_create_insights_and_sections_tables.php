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
            $table->string('title');
            $table->text('description');
            $table->json('keywords');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('insight_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insight_id')->constrained('insights')->onDelete('cascade');
            $table->string('header');
            $table->text('content_markdown');
            $table->string('image_path');
            $table->enum('background_color', ['white', 'yellow', 'blue']);
            $table->integer('order');
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
