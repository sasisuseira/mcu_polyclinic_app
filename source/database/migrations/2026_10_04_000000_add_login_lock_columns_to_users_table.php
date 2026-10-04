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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('login_attempts')->default(0)->after('password');
            $table->unsignedInteger('login_max_attempts')->default(3)->after('login_attempts');
            $table->unsignedInteger('login_hold_minutes')->default(10)->after('login_max_attempts');
            $table->timestamp('login_locked_until')->nullable()->after('login_hold_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'login_attempts',
                'login_max_attempts',
                'login_hold_minutes',
                'login_locked_until',
            ]);
        });
    }
};
