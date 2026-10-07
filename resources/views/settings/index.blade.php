@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto">
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Favicon Section -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Favicon</h3>
                <p class="text-gray-600 text-sm mb-4">Ikon yang ditampilkan di tab browser. Format: JPEG, PNG, JPG, GIF, ICO (Max: 2MB)</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($favicon)
                            <div class="mb-4">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
                                <div class="flex items-center justify-center w-24 h-24 bg-gray-100 rounded-lg border-2 border-gray-300">
                                    <img src="{{ asset('storage/' . $favicon) }}" alt="Favicon" class="w-16 h-16">
                                </div>
                            </div>
                            <form action="{{ route('settings.delete-favicon') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-sm">Hapus Favicon</button>
                            </form>
                        @else
                            <p class="text-gray-500 text-sm mb-3">Belum ada favicon</p>
                        @endif
                    </div>
                    <div>
                        <label for="favicon" class="block text-gray-700 font-semibold mb-2">
                            Upload Favicon Baru
                        </label>
                        <input 
                            type="file" 
                            id="favicon" 
                            name="favicon" 
                            class="input-field @error('favicon') border-red-500 @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/gif,image/x-icon,.ico"
                        >
                        @error('favicon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Logo Section -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Logo Aplikasi</h3>
                <p class="text-gray-600 text-sm mb-4">Logo yang ditampilkan di pojok kiri dekat "Absensi". Format: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($logo)
                            <div class="mb-4">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
                                <div class="flex items-center justify-center w-32 h-32 bg-gray-100 rounded-lg border-2 border-gray-300">
                                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="max-w-full max-h-full">
                                </div>
                            </div>
                            <form action="{{ route('settings.delete-logo') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-sm">Hapus Logo</button>
                            </form>
                        @else
                            <p class="text-gray-500 text-sm mb-3">Belum ada logo</p>
                        @endif
                    </div>
                    <div>
                        <label for="logo" class="block text-gray-700 font-semibold mb-2">
                            Upload Logo Baru
                        </label>
                        <input 
                            type="file" 
                            id="logo" 
                            name="logo" 
                            class="input-field @error('logo') border-red-500 @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                        >
                        @error('logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Login Background Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Background Login Page</h3>
                <p class="text-gray-600 text-sm mb-4">Background image untuk halaman login. Format: JPEG, PNG, JPG, GIF (Max: 5MB)</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($login_background)
                            <div class="mb-4">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
                                <div class="w-full h-48 bg-gray-100 rounded-lg border-2 border-gray-300 overflow-hidden">
                                    <img src="{{ asset('storage/' . $login_background) }}" alt="Login Background" class="w-full h-full object-cover">
                                </div>
                            </div>
                            <form action="{{ route('settings.delete-background') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-sm">Hapus Background</button>
                            </form>
                        @else
                            <p class="text-gray-500 text-sm mb-3">Belum ada background</p>
                        @endif
                    </div>
                    <div>
                        <label for="login_background" class="block text-gray-700 font-semibold mb-2">
                            Upload Background Baru
                        </label>
                        <input 
                            type="file" 
                            id="login_background" 
                            name="login_background" 
                            class="input-field @error('login_background') border-red-500 @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                        >
                        @error('login_background')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
