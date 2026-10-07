<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Absensi Karyawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-600 to-blue-900 min-h-screen flex items-center justify-center py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-900">Pendaftaran</h1>
                <p class="text-gray-600 mt-2">Buat akun baru</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="input-field @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-semibold mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        class="input-field @error('username') border-red-500 @enderror"
                        placeholder="Masukkan username"
                        required
                    >
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                        Email (Opsional)
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="input-field @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email (opsional)"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="unit_id" class="block text-gray-700 font-semibold mb-2">
                        Unit
                    </label>
                    <select 
                        id="unit_id" 
                        name="unit_id" 
                        class="input-field @error('unit_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password (minimal 8 karakter)"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">
                        Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="input-field"
                        placeholder="Konfirmasi password"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="btn-primary w-full mb-4"
                >
                    Daftar
                </button>
            </form>

            <div class="text-center text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    Login di sini
                </a>
            </div>
        </div>

        <div class="text-center mt-8 text-white text-sm">
            <p>Sistem Absensi Karyawan Yayasan © 2026</p>
        </div>
    </div>
</body>
</html>
