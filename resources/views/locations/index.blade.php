@extends('layouts.app')

@section('title', 'Setting Lokasi')
@section('page-title', 'Setting Lokasi Geofencing')

@section('content')
<div class="card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Lokasi Gedung</h3>
        <a href="{{ route('locations.create') }}" class="btn-primary">
            + Tambah Lokasi
        </a>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
        <p class="text-blue-900 text-sm">
            <strong>Informasi:</strong> Setiap lokasi memiliki radius 10 meter. Guru hanya bisa melakukan absensi jika berada dalam radius tersebut dari titik lokasi gedung unit mereka.
        </p>
    </div>

    @if ($locations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Lokasi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Unit</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Koordinat</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Radius</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $location->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $location->unit->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <div class="text-sm">
                                    <p>Lat: {{ number_format($location->latitude, 6) }}</p>
                                    <p>Long: {{ number_format($location->longitude, 6) }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">
                                    {{ $location->radius }}m
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($location->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <a href="{{ route('locations.edit', $location) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $locations->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="text-gray-600 text-lg mb-4">Belum ada lokasi</p>
            <a href="{{ route('locations.create') }}" class="btn-primary">
                Tambah Lokasi Pertama
            </a>
        </div>
    @endif
</div>
@endsection
