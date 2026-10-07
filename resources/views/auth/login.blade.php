<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi Karyawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $favicon = \App\Models\Setting::get('favicon');
        $loginBackground = \App\Models\Setting::get('login_background');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif
    @if($loginBackground)
        <style>
            body {
                background-image: url('{{ asset('storage/' . $loginBackground) }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
                pointer-events: none;
                z-index: -1;
            }
        </style>
    @endif
</head>
<body @if(!$loginBackground) class="bg-gradient-to-br from-blue-600 to-blue-900 min-h-screen flex items-center justify-center" @else class="min-h-screen flex items-center justify-center" @endif>
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-900">Absensi Karyawan</h1>
                <p class="text-gray-600 mt-2">Sistem Manajemen Absensi dengan Geofencing</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="login" class="block text-gray-700 font-semibold mb-2">
                        Username atau Email
                    </label>
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        value="{{ old('login') }}"
                        class="input-field @error('login') border-red-500 @enderror"
                        placeholder="Masukkan username atau email"
                        required
                    >
                    @error('login')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="w-4 h-4 text-blue-600 rounded"
                    >
                    <label for="remember" class="ml-2 text-gray-700">
                        Ingat saya
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="btn-primary w-full mb-4"
                >
                    Login
                </button>
            </form>

            <div class="text-center text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    Daftar di sini
                </a>
            </div>
        </div>

        <div class="text-center mt-8 text-white text-sm">
            <p>Sistem Absensi Karyawan Yayasan © 2026</p>
        </div>
    </div>
</body>
</html>
