<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop old employee_profiles table
        Schema::dropIfExists('employee_profiles');
        
        // Create new table without unique constraint on nik
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nik')->nullable(); // NIK Pegawai - removed unique constraint
            $table->string('address')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('education', ['SMA', 'Sarjana'])->nullable();
            $table->date('tmt')->nullable(); // Tanggal Masuk Tugas
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new table
        Schema::dropIfExists('employee_profiles');
        
        // Recreate old table with unique constraint
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nik')->unique();
            $table->string('address')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('education', ['SMA', 'Sarjana'])->nullable();
            $table->date('tmt')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
