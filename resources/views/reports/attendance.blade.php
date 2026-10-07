@extends('layouts.app')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="card mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h3>
    
    <form action="{{ route('reports.attendance') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label for="date_from" class="block text-gray-700 font-semibold mb-2">
                Dari Tanggal
            </label>
            <input 
                type="date" 
                id="date_from" 
                name="date_from" 
                value="{{ request('date_from') }}"
                class="input-field"
            >
        </div>

        <div>
            <label for="date_to" class="block text-gray-700 font-semibold mb-2">
                Sampai Tanggal
            </label>
            <input 
                type="date" 
                id="date_to" 
                name="date_to" 
                value="{{ request('date_to') }}"
                class="input-field"
            >
        </div>

        <div>
            <label for="user_id" class="block text-gray-700 font-semibold mb-2">
                Karyawan
            </label>
            <select 
                id="user_id" 
                name="user_id" 
                class="input-field"
            >
                <option value="">Semua Karyawan</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-gray-700 font-semibold mb-2">
                Status
            </label>
            <select 
                id="status" 
                name="status" 
                class="input-field"
            >
                <option value="">Semua Status</option>
                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absen</option>
                <option value="early_out" {{ request('status') == 'early_out' ? 'selected' : '' }}>Pulang Cepat</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">
                Filter
            </button>
        </div>
    </form>
</div>

<div class="card">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">Daftar Absensi</h3>

    @if ($attendances->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Karyawan</th>
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
                                {{ $attendance->user->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $attendance->location->name ?? '-' }}
                            </td>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="text-gray-600 text-lg">Tidak ada data absensi</p>
        </div>
    @endif
</div>
@endsection
