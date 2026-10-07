@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Karyawan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Attendance Status Card -->
    <div class="card md:col-span-2">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Absensi Hari Ini</h3>
        
        @if ($todayAttendance)
            <div class="space-y-4">
                @if ($todayAttendance->check_in_time)
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="text-gray-600 text-sm">Check-In</p>
                            <p class="text-green-600 font-bold text-lg">{{ $todayAttendance->check_in_time->format('H:i:s') }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @else
                    <a href="{{ route('attendance.index') }}" class="btn-primary w-full py-3 text-center">
                        Lakukan Check-In Sekarang
                    </a>
                @endif

                @if ($todayAttendance->check_out_time)
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div>
                            <p class="text-gray-600 text-sm">Check-Out</p>
                            <p class="text-blue-600 font-bold text-lg">{{ $todayAttendance->check_out_time->format('H:i:s') }}</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @elseif ($todayAttendance->check_in_time)
                    <a href="{{ route('attendance.index') }}" class="btn-primary w-full py-3 text-center">
                        Lakukan Check-Out Sekarang
                    </a>
                @endif

                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600 text-sm">Status</p>
                    <p class="font-bold text-lg">
                        @if ($todayAttendance->status === 'present')
                            <span class="text-green-600">✓ Hadir</span>
                        @elseif ($todayAttendance->status === 'late')
                            <span class="text-orange-600">⚠ Terlambat</span>
                        @else
                            <span class="text-gray-600">Absen</span>
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 mb-4">Belum ada data absensi hari ini</p>
                <a href="{{ route('attendance.index') }}" class="btn-primary">
                    Mulai Check-In
                </a>
            </div>
        @endif
    </div>

    <!-- Statistics Card -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Absensi</h3>
        <div class="space-y-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-gray-600 text-sm">Hadir</p>
                <p class="text-3xl font-bold text-green-600">{{ $attendanceStats['present'] }}</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <p class="text-gray-600 text-sm">Terlambat</p>
                <p class="text-3xl font-bold text-orange-600">{{ $attendanceStats['late'] }}</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <p class="text-gray-600 text-sm">Absen</p>
                <p class="text-3xl font-bold text-red-600">{{ $attendanceStats['absent'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance History -->
<div class="card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Riwayat Absensi Terbaru</h3>
        <a href="{{ route('attendance.history') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
            Lihat Semua →
        </a>
    </div>

    @if ($recentAttendances->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Check-In</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Check-Out</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentAttendances as $attendance)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $attendance->attendance_date->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                @if ($attendance->check_in_time)
                                    <span class="text-green-600 font-semibold">{{ $attendance->check_in_time->format('H:i:s') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($attendance->check_out_time)
                                    <span class="text-blue-600 font-semibold">{{ $attendance->check_out_time->format('H:i:s') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($attendance->status === 'present')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Hadir</span>
                                @elseif ($attendance->status === 'late')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">⚠ Terlambat</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ Absen</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-600">Belum ada riwayat absensi</p>
        </div>
    @endif
</div>
@endsection
