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
    public function up(): void
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('data');
            $table->foreignId('telegram_chat_id');
            $table->text('text');
            $table->boolean('is_incoming');
            $table->boolean('is_outgoing');
            $table->string('from_username', 35);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
