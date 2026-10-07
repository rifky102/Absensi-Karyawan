@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User - ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                        Nama Lengkap <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="input-field @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-gray-700 font-semibold mb-2">
                        Username <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username', $user->username) }}"
                        class="input-field @error('username') border-red-500 @enderror"
                        placeholder="Masukkan username"
                        required
                    >
                    @error('username')
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
                        value="{{ old('email', $user->email) }}"
                        class="input-field @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-gray-700 font-semibold mb-2">
                        Role <span class="text-red-600">*</span>
                    </label>
                    <select 
                        id="role" 
                        name="role" 
                        class="input-field @error('role') border-red-500 @enderror"
                        required
                    >
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_id" class="block text-gray-700 font-semibold mb-2">
                        Unit <span class="text-red-600">*</span>
                    </label>
                    <select 
                        id="unit_id" 
                        name="unit_id" 
                        class="input-field @error('unit_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-gray-700 font-semibold mb-2">
                        Status
                    </label>
                    <select 
                        id="is_active" 
                        name="is_active" 
                        class="input-field"
                    >
                        <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Foto Profil & Permission</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="profile_photo" class="block text-gray-700 font-semibold mb-2">
                            Foto Profil
                        </label>
                        @if($user->profile_photo)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="h-20 w-20 rounded-lg object-cover">
                            </div>
                        @endif
                        <input 
                            type="file" 
                            id="profile_photo" 
                            name="profile_photo" 
                            class="input-field @error('profile_photo') border-red-500 @enderror"
                            accept="image/*"
                        >
                        <p class="text-gray-500 text-xs mt-1">Ukuran max: 2MB (JPEG, PNG, GIF)</p>
                        @error('profile_photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center pt-4">
                        <input 
                            type="checkbox" 
                            id="is_administrator" 
                            name="is_administrator" 
                            value="1"
                            class="w-4 h-4 text-blue-600 rounded"
                            {{ old('is_administrator', $user->is_administrator) ? 'checked' : '' }}
                        >
                        <label for="is_administrator" class="ml-2 text-gray-700 font-semibold">
                            Jadikan Administrator
                        </label>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Data Profil Karyawan</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nik" class="block text-gray-700 font-semibold mb-2">
                            NIK Pegawai
                        </label>
                        <input 
                            type="text" 
                            id="nik" 
                            name="nik" 
                            value="{{ old('nik', $user->profile?->nik ?? '') }}"
                            class="input-field @error('nik') border-red-500 @enderror"
                            placeholder="Masukkan NIK"
                        >
                        @error('nik')
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
                            value="{{ old('phone', $user->profile?->phone ?? '') }}"
                            class="input-field @error('phone') border-red-500 @enderror"
                            placeholder="Masukkan nomor telepon"
                        >
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="place_of_birth" class="block text-gray-700 font-semibold mb-2">
                            Tempat Lahir
                        </label>
                        <input 
                            type="text" 
                            id="place_of_birth" 
                            name="place_of_birth" 
                            value="{{ old('place_of_birth', $user->profile?->place_of_birth ?? '') }}"
                            class="input-field @error('place_of_birth') border-red-500 @enderror"
                            placeholder="Masukkan tempat lahir"
                        >
                        @error('place_of_birth')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-gray-700 font-semibold mb-2">
                            Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            id="date_of_birth" 
                            name="date_of_birth" 
                            value="{{ old('date_of_birth', $user->profile?->date_of_birth?->format('Y-m-d')) }}"
                            class="input-field @error('date_of_birth') border-red-500 @enderror"
                        >
                        @error('date_of_birth')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="education" class="block text-gray-700 font-semibold mb-2">
                            Pendidikan Terakhir
                        </label>
                        <select 
                            id="education" 
                            name="education" 
                            class="input-field @error('education') border-red-500 @enderror"
                        >
                            <option value="">Pilih Pendidikan</option>
                            <option value="SMA" {{ old('education', $user->profile?->education ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="Sarjana" {{ old('education', $user->profile?->education ?? '') == 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                        </select>
                        @error('education')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tmt" class="block text-gray-700 font-semibold mb-2">
                            TMT (Tanggal Mulai Tugas)
                        </label>
                        <input 
                            type="date" 
                            id="tmt" 
                            name="tmt" 
                            value="{{ old('tmt', $user->profile?->tmt?->format('Y-m-d')) }}"
                            class="input-field @error('tmt') border-red-500 @enderror"
                        >
                        @error('tmt')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-gray-700 font-semibold mb-2">
                            Alamat
                        </label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3"
                            class="input-field @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat"
                        >{{ old('address', $user->profile?->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
