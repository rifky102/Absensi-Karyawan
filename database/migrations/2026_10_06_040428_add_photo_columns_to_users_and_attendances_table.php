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
        // Add photo column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('is_active');
            $table->boolean('is_administrator')->default(false)->after('profile_photo');
        });

        // Add photo column to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('checkin_photo')->nullable()->after('check_in_longitude');
            $table->string('checkout_photo')->nullable()->after('check_out_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'is_administrator']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['checkin_photo', 'checkout_photo']);
        });
    }
};
