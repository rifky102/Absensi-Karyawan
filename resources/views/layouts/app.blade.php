<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi Karyawan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    @php
        $favicon = \App\Models\Setting::get('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif
</head>
<body class="bg-gray-100">
    @if (auth()->check())
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <div class="w-64 bg-blue-900 text-white shadow-lg hidden md:block">
                <div class="p-6 border-b border-blue-800">
                    @php
                        $logo = \App\Models\Setting::get('logo');
                    @endphp
                    @if($logo)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-12 w-auto">
                        </div>
                    @endif
                    <h1 class="text-2xl font-bold">Absensi</h1>
                    <p class="text-blue-200 text-sm">Sistem Manajemen</p>
                </div>

                <nav class="mt-6">
                    <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('dashboard*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l4-4m0 0l4 4m-4-4V5"></path>
                            </svg>
                            Dashboard
                        </span>
                    </a>

                    <a href="{{ route('attendance.index') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('attendance*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0A5 5 0 017 12z"></path>
                            </svg>
                            Absensi
                        </span>
                    </a>

                    @if (auth()->user()->isAdmin())
                        <div class="px-6 py-3 text-blue-300 text-xs font-bold uppercase tracking-wider mt-4">
                            Admin
                        </div>

                        <a href="{{ route('users.index') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('users*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                                </svg>
                                Manajemen User
                            </span>
                        </a>

                        <a href="{{ route('locations.index') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('locations*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Setting Lokasi
                            </span>
                        </a>

                        <a href="{{ route('reports.attendance') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('reports*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Laporan
                            </span>
                        </a>
                    @endif

                    <div class="px-6 py-3 text-blue-300 text-xs font-bold uppercase tracking-wider mt-4">
                        Akun
                    </div>

                    <a href="{{ route('profile.edit') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('profile*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Profil
                        </span>
                    </a>

                    @if(auth()->user()->is_administrator)
                        <a href="{{ route('settings.index') }}" class="block px-6 py-3 hover:bg-blue-800 transition {{ request()->routeIs('settings*') ? 'bg-blue-800 border-l-4 border-blue-300' : '' }}">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan
                        </span>
                    </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="block px-6 py-3 hover:bg-red-800 transition">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Top Navbar -->
                <nav class="bg-white shadow-md sticky top-0 z-40">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div class="flex items-center">
                            <button id="menu-toggle" class="md:hidden text-gray-600 hover:text-gray-900">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <h2 class="text-gray-800 font-semibold ml-4">@yield('page-title', 'Dashboard')</h2>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600 text-sm">{{ auth()->user()->name }}</span>
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="flex-1 overflow-auto p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <h3 class="font-bold mb-2">Terjadi kesalahan:</h3>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    @else
        @yield('content')
    @endif

    <script>
        document.getElementById('menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.md\\:block')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
