<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('captures', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable();
        });
        DB::table('captures')->update(['user_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('captures', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
