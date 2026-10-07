@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="card">
            <div class="text-center">
                @if(auth()->user()->profile_photo)
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-20 h-20 rounded-full mx-auto mb-4 object-cover">
                @else
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <h3 class="text-lg font-semibold text-gray-800">{{ auth()->user()->name }}</h3>
                <div class="mt-4 pt-4 border-t">
                    <p class="text-gray-600 text-sm">
                        <strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    </p>
                    <p class="text-gray-600 text-sm mt-2">
                        <strong>Unit:</strong> {{ auth()->user()->unit->name }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Forms -->
        <div class="md:col-span-2 space-y-6">
            <!-- Update Profile Form -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Profil</h3>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-gray-700 font-semibold mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', auth()->user()->name) }}"
                            class="input-field @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 font-semibold mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}"
                            class="input-field @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-gray-700 font-semibold mb-2">
                            Nomor Telepon
                        </label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', auth()->user()->profile->phone ?? '') }}"
                            class="input-field @error('phone') border-red-500 @enderror"
                        >
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-gray-700 font-semibold mb-2">
                            Alamat
                        </label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3"
                            class="input-field @error('address') border-red-500 @enderror"
                        >{{ old('address', auth()->user()->profile->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Update Photo Form -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Foto Profil</h3>

                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="profile_photo" class="block text-gray-700 font-semibold mb-2">
                            Foto Profil (Maksimal 2MB)
                        </label>
                        <input 
                            type="file" 
                            id="profile_photo" 
                            name="profile_photo" 
                            accept="image/*"
                            class="input-field @error('profile_photo') border-red-500 @enderror"
                            required
                        >
                        <p class="text-gray-500 text-sm mt-2">Format: JPEG, PNG, JPG, GIF</p>
                        @error('profile_photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Upload Foto
                    </button>
                </form>
            </div>
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h3>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-gray-700 font-semibold mb-2">
                            Password Saat Ini
                        </label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="input-field @error('current_password') border-red-500 @enderror"
                            required
                        >
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-gray-700 font-semibold mb-2">
                            Password Baru (minimal 8 karakter)
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="input-field @error('password') border-red-500 @enderror"
                            required
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="input-field"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Ubah Password
                    </button>
                </form>
            </div>

            <!-- Profile Information -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Data Karyawan</h3>

                @if(auth()->user()->profile)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">NIK:</span>
                            <span class="font-semibold">{{ auth()->user()->profile->nik ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600">Tempat Lahir:</span>
                            <span class="font-semibold">{{ auth()->user()->profile->place_of_birth ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600">Tanggal Lahir:</span>
                            <span class="font-semibold">{{ auth()->user()->profile->date_of_birth?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600">Pendidikan:</span>
                            <span class="font-semibold">{{ auth()->user()->profile->education ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600">TMT:</span>
                            <span class="font-semibold">{{ auth()->user()->profile->tmt?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="text-sm text-gray-500 border-t pt-3">
                            <p>Untuk mengubah data karyawan lainnya, hubungi Administrator.</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-700 text-sm">Data karyawan belum tersedia. Hubungi Administrator untuk menambahkan data profile Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
