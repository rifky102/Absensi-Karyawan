<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed units first
        $this->call(UnitSeeder::class);

        // Create admin user
        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@yayasan.com',
            'password' => bcrypt('admin123456'),
            'role' => 'admin',
            'unit_id' => 1,
            'is_administrator' => true,
        ]);
    }
}
