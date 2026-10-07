@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Daftar User</h3>
        <a href="{{ route('users.create') }}" class="btn-primary">
            + Tambah User
        </a>
    </div>

    @if ($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Username</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Unit</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $user->username }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $user->email ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $user->unit->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($user->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
            {{ $users->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
            </svg>
            <p class="text-gray-600 text-lg mb-4">Belum ada user</p>
            <a href="{{ route('users.create') }}" class="btn-primary">
                Tambah User Pertama
            </a>
        </div>
    @endif
</div>
@endsection
