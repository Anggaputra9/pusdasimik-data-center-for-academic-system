    
    <!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pusat Data') - Sistem Informasi Akademik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Initialize dark mode BEFORE page renders to prevent flash
        (function() {
            // Check if user has a preference, default to light mode
            const darkModePreference = localStorage.getItem('darkMode');
            
            // Only enable dark mode if explicitly set to 'true'
            if (darkModePreference === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                // Ensure light mode by removing dark class
                document.documentElement.classList.remove('dark');
                // Set default preference if not set
                if (darkModePreference === null) {
                    localStorage.setItem('darkMode', 'false');
                }
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200" 
      x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden" @keydown.escape.window="sidebarOpen = false">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" 
             aria-hidden="true"></div>

        <!-- Mobile sidebar -->
        <aside x-show="sidebarOpen"
               x-cloak
               @click.away="sidebarOpen = false"
               x-transition:enter="transition ease-in-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col lg:hidden">
            <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200">
                <!-- Logo -->
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center justify-between px-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 dark:bg-blue-500">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Pusat Data</h1>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Admin Panel</p>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Navigation -->
                    <nav class="mt-8 flex-1 space-y-1 px-3">
                        <a href="{{ route('admin.mahasiswa.index') }}" 
                           class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.mahasiswa.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Data Mahasiswa
                        </a>
                        <a href="{{ route('admin.dosen.index') }}" 
                           class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.dosen.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.dosen.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Data Dosen
                        </a>
                        <a href="{{ route('admin.unit.index') }}" 
                           class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.unit.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.unit.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Data Unit
                        </a>
                        <a href="{{ route('admin.api-client.index') }}"
                           class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.api-client.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.api-client.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Klien API
                        </a>
                    </nav>
                </div>
                <!-- Footer -->
                <div class="flex flex-shrink-0 border-t border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                            <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Administrator</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">admin@pusatdata.ac.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Desktop sidebar -->
        <aside class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex w-64 flex-col">
                <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-200">
                    <!-- Logo -->
                    <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                        <div class="flex flex-shrink-0 items-center px-6">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 dark:bg-blue-500">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Pusat Data</h1>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Admin Panel</p>
                                </div>
                            </div>
                        </div>
                        <!-- Navigation -->
                        <nav class="mt-8 flex-1 space-y-1 px-3">
                            <a href="{{ route('admin.mahasiswa.index') }}" 
                               class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.mahasiswa.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Data Mahasiswa
                            </a>
                            <a href="{{ route('admin.dosen.index') }}" 
                               class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.dosen.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.dosen.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Data Dosen
                            </a>
                            <a href="{{ route('admin.unit.index') }}" 
                               class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.unit.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.unit.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Data Unit
                            </a>
                            <a href="{{ route('admin.api-client.index') }}"
                               class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.api-client.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} transition-colors duration-150">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.api-client.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Klien API
                            </a>
                        </nav>
                    </div>
                    <!-- Footer -->
                    <div class="flex flex-shrink-0 border-t border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Administrator</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">admin@pusatdata.ac.id</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="w-full">
                <div class="relative z-10 flex h-16 flex-shrink-0 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm transition-colors duration-200">
                    <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-1 items-center">
                            <!-- Hamburger button for mobile -->
                            <button @click="sidebarOpen = true" 
                                    class="mr-4 inline-flex items-center justify-center rounded-lg p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden"
                                    aria-label="Open sidebar">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">@yield('title', 'Dashboard')</h2>
                        </div>
                        <div class="ml-4 flex items-center space-x-4">
                            <!-- Dark Mode Toggle -->
                            <button onclick="toggleDarkMode()" 
                                    id="darkModeToggle"
                                    class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-150"
                                    title="Toggle Dark Mode">
                                <svg id="moonIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg id="sunIcon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>
                            
                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-3 py-1 text-xs font-medium text-green-800 dark:text-green-400">
                                <span class="mr-1.5 h-2 w-2 rounded-full bg-green-400 dark:bg-green-500"></span>
                                API Active
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
                <div class="py-6">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        @if(session('success'))
                            <div class="mb-6 rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-400">Terdapat beberapa error:</h3>
                                        <ul class="mt-2 list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-400">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        // Dark Mode Toggle Function
        function toggleDarkMode() {
            const html = document.documentElement;
            const moonIcon = document.getElementById('moonIcon');
            const sunIcon = document.getElementById('sunIcon');
            
            console.log('BEFORE toggle - HTML classes:', html.className);
            console.log('BEFORE toggle - Has dark class:', html.classList.contains('dark'));
            
            // Toggle dark class
            html.classList.toggle('dark');
            
            console.log('AFTER toggle - HTML classes:', html.className);
            console.log('AFTER toggle - Has dark class:', html.classList.contains('dark'));
            
            // Check if dark mode is now active
            const isDark = html.classList.contains('dark');
            
            // Save to localStorage
            localStorage.setItem('darkMode', isDark);
            
            // Toggle icons
            if (isDark) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
            }
            
            console.log('Dark mode toggled:', isDark);
            console.log('Body background should be:', isDark ? 'dark (gray-900)' : 'light (gray-100)');
        }
        
        // Initialize icon visibility on page load
        (function() {
            const isDark = localStorage.getItem('darkMode') === 'true';
            const moonIcon = document.getElementById('moonIcon');
            const sunIcon = document.getElementById('sunIcon');
            
            if (isDark) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
