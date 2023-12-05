<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_resource_access', 100)->default('None');
            $table->string('capture_resource_access', 100)->default('None');
        });

        $user = User::find(1);
        $user->user_resource_access = 'All';
        $user->capture_resource_access = 'All';
        $user->save();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_resource_access');
            $table->dropColumn('capture_resource_access');
        });

    }
};
