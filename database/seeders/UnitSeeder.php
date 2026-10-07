<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'name' => 'TK',
            'description' => 'Taman Kanak-kanak',
            'building_name' => 'Gedung TK',
            'is_active' => true,
        ]);

        Unit::create([
            'name' => 'SD',
            'description' => 'Sekolah Dasar',
            'building_name' => 'Gedung SD',
            'is_active' => true,
        ]);

        Unit::create([
            'name' => 'SMP',
            'description' => 'Sekolah Menengah Pertama',
            'building_name' => 'Gedung SMP',
            'is_active' => true,
        ]);

        Unit::create([
            'name' => 'Karyawan',
            'description' => 'Unit Karyawan (Non-Guru)',
            'building_name' => 'Karyawan',
            'is_active' => true,
        ]);
    }
}
