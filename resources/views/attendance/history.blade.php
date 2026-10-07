@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')

@section('content')
<div class="card">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">Riwayat Absensi Lengkap</h3>

    @if ($attendances->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Lokasi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Check-In</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Check-Out</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $attendance->attendance_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $attendance->location->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($attendance->check_in_time)
                                    <span class="text-green-600 font-semibold">{{ $attendance->check_in_time->format('H:i:s') }}</span>
                                    @if ($attendance->check_in_latitude && $attendance->check_in_longitude)
                                        <div class="text-gray-500 text-xs mt-1">
                                            {{ $attendance->check_in_latitude }}, {{ $attendance->check_in_longitude }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($attendance->check_out_time)
                                    <span class="text-blue-600 font-semibold">{{ $attendance->check_out_time->format('H:i:s') }}</span>
                                    @if ($attendance->check_out_latitude && $attendance->check_out_longitude)
                                        <div class="text-gray-500 text-xs mt-1">
                                            {{ $attendance->check_out_latitude }}, {{ $attendance->check_out_longitude }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($attendance->status === 'present')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Hadir</span>
                                @elseif ($attendance->status === 'late')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">⚠ Terlambat</span>
                                @elseif ($attendance->status === 'early_out')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⬆ Pulang Cepat</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ Absen</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 text-lg">Belum ada riwayat absensi</p>
        </div>
    @endif
</div>
@endsection
