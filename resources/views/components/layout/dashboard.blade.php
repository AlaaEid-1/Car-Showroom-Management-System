<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Workspace | Alaa Motors' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Style and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">
    <div class="h-screen overflow-hidden flex">
        
        <!-- Sidebar Component -->
        <x-sidebar.dashboard />

        <!-- Main Workspace Frame -->
        <div class="flex flex-col flex-1 min-w-0">
            <!-- Dashboard Top Nav -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger Button -->
                    <button type="button" 
                            class="text-slate-500 hover:text-slate-900 lg:hidden focus:outline-none"
                            @click="sidebarOpen = true">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    
                    <!-- Search Indicator or Title -->
                    <span class="text-sm font-semibold text-slate-500 tracking-wider uppercase">
                        {{ $header ?? 'Workspace Portal' }}
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    <!-- User Profile Dropdown Placeholder (Standard Alpine.js UI) -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button type="button" 
                                class="flex items-center gap-2 focus:outline-none" 
                                @click="open = !open">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-luxury-charcoal text-white font-bold text-sm">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </span>
                            <span class="text-sm font-medium text-slate-700 hidden sm:inline">
                                {{ Auth::user()->name ?? 'User' }}
                            </span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2.5 w-48 origin-top-right rounded-lg bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none z-50 border border-slate-100" 
                             style="display: none;">
                            <span class="block px-4 py-2 text-xs text-slate-400">Signed in as: <strong class="text-slate-600 font-medium">{{ Auth::user()->role ?? 'Guest' }}</strong></span>
                            
                            <hr class="border-slate-100 my-1">
                            
                            <!-- Profile Actions -->
                            <a href="{{ route('inquiries.index') }}#profile" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                Edit Profile
                            </a>
                            
                            <hr class="border-slate-100 my-1">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 max-w-7xl w-full mx-auto">
                <!-- Status & Session Alerts -->
                @if (session('success'))
                    <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex gap-3 text-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div class="flex-1 font-medium">{{ session('success') }}</div>
                        <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-800 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-lg bg-rose-50 border border-rose-200 p-4 text-rose-800 flex gap-3 text-sm" x-data="{ show: true }" x-show="show">
                        <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div class="flex-1 font-medium">{{ session('error') }}</div>
                        <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-800 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
