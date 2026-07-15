<!-- Sidebar Container -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
     class="fixed inset-y-0 left-0 z-40 w-64 bg-luxury-charcoal text-slate-300 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:h-screen lg:shrink-0 border-r border-slate-800">
    
    <!-- Sidebar Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/80 bg-luxury-charcoal">
        <a href="/" class="flex items-center space-x-2">
            <span class="text-lg font-bold tracking-widest text-white uppercase">
                ALAA<span class="text-luxury-gold">MOTORS</span>
            </span>
        </a>

        <!-- Mobile Sidebar Close Button -->
        <button type="button" 
                class="lg:hidden text-slate-400 hover:text-white"
                @click="sidebarOpen = false">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
        @php
            $role = Auth::user()->role ?? 'customer';
            $route = Route::currentRouteName();
        @endphp

        <!-- DEALER LINKS -->
        @if ($role === 'dealer')
            <div class="px-3 mb-2 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                Dealer Console
            </div>
            
            <a href="{{ route('dashboarddealer.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'dashboarddealer.dashboard' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5z"/>
                </svg>
                Overview Dashboard
            </a>

            <a href="{{ route('dashboarddealer.showroom.edit') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'dashboarddealer.showroom.edit' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-18v18M2.25 5.25h19.5" />
                </svg>
                My Showroom
            </a>

            <a href="{{ route('dashboarddealer.cars.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'dashboarddealer.cars') ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.09-1.451M17.25 18.75h-3m3 0a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 18.507 4.5h.386a1.125 1.125 0 0 1 1.12 1.243l-1.264 12A1.125 1.125 0 0 1 17.25 18.75Z" />
                </svg>
                My Cars Inventory
            </a>

            <a href="{{ route('inquiries.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'inquiries') ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                Inquiries Workspace
            </a>

            <a href="{{ route('dashboarddealer.test-drives.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Str::startsWith($route, 'dashboarddealer.test-drives') ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Test Drives
            </a>
        @endif

        <!-- ADMIN LINKS -->
        @if ($role === 'admin')
            <div class="px-3 mb-2 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                Admin Console
            </div>

            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.dashboard' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5z"/>
                </svg>
                Overview Metrics
            </a>

            <a href="{{ route('admin.users') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.users' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 20.5a11.384 11.384 0 0 1-4.918-1.263v-.109m0-1.076c0-1.113.285-2.16.786-3.07M5.089 18.082a9.38 9.38 0 0 0-2.625.372 9.337 9.337 0 0 0-4.121-.952 4.125 4.125 0 0 0 7.533-2.493M10.5 2.25a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM19.5 5.25a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM10.5 9.75a4.875 4.875 0 0 1 4.875 4.875v.375H5.625v-.375A4.875 4.875 0 0 1 10.5 9.75Z" />
                </svg>
                Users Directory
            </a>

            <a href="{{ route('admin.dealers') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.dealers' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615m-7.5 0V5.25m3.75 4.099V5.25m3.75 4.099V5.25m-.75-3h7.5a.75.75 0 0 1 .75.75V9.05m-7.5-7.5a.75.75 0 0 0-.75.75V9.05m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615m0 0V5.25m-2.25 10.5a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5ZM6 15a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-.75.75h-1.5A.75.75 0 0 1 6 15.75v-1.5Zm11.25-4.5a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5Zm-6-6a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5Z" />
                </svg>
                Dealers Directory
            </a>

            <a href="{{ route('admin.cars') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.cars' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.09-1.451M17.25 18.75h-3m3 0a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 18.507 4.5h.386a1.125 1.125 0 0 1 1.12 1.243l-1.264 12A1.125 1.125 0 0 1 17.25 18.75Z" />
                </svg>
                Manage Vehicles
            </a>

            <a href="{{ route('admin.showrooms') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.showrooms' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-18v18M2.25 5.25h19.5" />
                </svg>
                Showrooms Directory
            </a>

            <a href="{{ route('admin.inquiries') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.inquiries' ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.408 1.562-2.954m0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                Global Inquiries
            </a>
        @endif

        <!-- CUSTOMER / GENERAL LINKS -->
        <div class="px-3 mb-2 text-xs font-semibold tracking-wider text-slate-500 uppercase pt-4">
            User Center
        </div>

        <a href="{{ route('inquiries.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ ($route === 'inquiries.index' && $role === 'customer') ? 'bg-luxury-gold text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            My Inquiries
        </a>

        <!-- Settings link context -->
        <a href="{{ Auth::user()->role === 'dealer' ? route('dashboarddealer.profile') : route('inquiries.index') . '#profile' }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors hover:bg-slate-800 text-slate-300">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            Profile Settings
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-800 bg-luxury-charcoal">
        <div class="flex items-center gap-3 px-2">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-[10px] text-slate-500 truncate uppercase tracking-wider">{{ Auth::user()->role ?? 'Guest' }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-rose-500 transition-colors" title="Sign Out">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M19 12H9m10 0-4-4m4 4-4 4" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
