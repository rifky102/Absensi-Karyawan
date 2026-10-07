@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Karyawan</p>
                <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $totalUsers }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Guru Card -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Guru</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $totalGuru }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v12m8-12v12M6.75 3.75h10.5a.75.75 0 01.75.75v14.5a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V4.5a.75.75 0 01.75-.75z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Absensi Hari Ini Card -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Absensi Hari Ini</p>
                <h3 class="text-3xl font-bold text-purple-600 mt-2">{{ $totalAttendanceToday }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0A5 5 0 017 12z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Hadir Hari Ini Card -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Hadir Hari Ini</p>
                <h3 class="text-3xl font-bold text-orange-600 mt-2">{{ $presentToday }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h-2m0 0H8m4 0v4m0-4V6m-2.5 3.5h5a1 1 0 011 1v3a1 1 0 01-1 1h-5a1 1 0 01-1-1v-3a1 1 0 011-1z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="space-y-2">
            <a href="{{ route('users.create') }}" class="btn-primary w-full block text-center text-sm py-2">
                + Tambah Karyawan
            </a>
            <a href="{{ route('locations.create') }}" class="btn-secondary w-full block text-center text-sm py-2">
                + Tambah Lokasi
            </a>
            <a href="{{ route('reports.attendance') }}" class="btn-secondary w-full block text-center text-sm py-2">
                Lihat Laporan
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card md:col-span-2">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Penting</h3>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <p class="text-blue-900 text-sm">
                <strong>Catatan:</strong> Sistem absensi dengan geofencing memerlukan lokasi yang sudah dikonfigurasi. 
                Pastikan semua lokasi gedung unit sudah ditambahkan di menu Setting Lokasi.
            </p>
        </div>
    </div>
</div>
@endsection
