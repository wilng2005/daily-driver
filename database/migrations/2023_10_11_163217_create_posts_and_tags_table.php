<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->text('slug')->nullable();
            $table->string('image_file', 100);
            $table->string('sequence_code')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->text('name')->nullable();
            $table->text('content')->nullable();
            $table->text('slug')->nullable();
            $table->string('image_file', 100);
            $table->string('sequence_code')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('post_id')->nullable();
            $table->bigInteger('tag_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('post_tag')
    }
};
