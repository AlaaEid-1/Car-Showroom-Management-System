<nav class="fixed top-0 left-0 right-0 h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 z-50 transition-all duration-300" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
        
        <!-- Brand Logo -->
        <a href="/" class="flex items-center space-x-2">
            <span class="text-xl font-bold tracking-widest text-slate-900 uppercase">
                ALAA<span class="text-luxury-gold">MOTORS</span>
            </span>
        </a>

        <!-- Desktop Navigation Items -->
        <div class="hidden md:flex items-center space-x-8">
            <a href="/" class="text-sm font-semibold text-slate-800 hover:text-luxury-gold transition-colors duration-200">
                Home
            </a>
            <a href="/cars/search" class="text-sm font-semibold text-slate-800 hover:text-luxury-gold transition-colors duration-200">
                Search Inventory
            </a>
        </div>

        <!-- Desktop Auth Action Items -->
        <div class="hidden md:flex items-center space-x-4">
            @auth
                <!-- Notification Bell Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-1.5 rounded-full hover:bg-slate-100 text-slate-600 hover:text-slate-800 transition-colors focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                        </svg>
                        @php
                            $unreadCount = Auth::user()->unreadNotifications()->count();
                        @endphp
                        @if ($unreadCount > 0)
                            <span class="absolute top-0.5 right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[8px] font-bold bg-rose-600 text-white leading-none transform translate-x-1/3 -translate-y-1/3">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition 
                         class="absolute right-0 mt-2.5 w-80 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 py-2 space-y-1"
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                            <span class="text-xs font-bold text-slate-800">Notifications</span>
                            @if ($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-extrabold uppercase tracking-wider text-luxury-gold hover:text-slate-800 transition-colors">
                                        Mark All Read
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                            @forelse (Auth::user()->unreadNotifications->take(5) as $noti)
                                <div class="px-4 py-3 hover:bg-slate-50/80 transition-colors text-left">
                                    <form action="{{ route('notifications.read', $noti->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full text-left focus:outline-none">
                                            <div class="text-[11px] font-bold text-slate-800 line-clamp-1">{{ $noti->data['title'] ?? 'Notification' }}</div>
                                            <div class="text-[10px] text-slate-500 mt-1 line-clamp-2 leading-normal">{{ $noti->data['message'] ?? '' }}</div>
                                            <div class="text-[9px] text-slate-400 mt-1 font-medium">{{ $noti->created_at->diffForHumans() }}</div>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center text-xs text-slate-400 font-medium">
                                    No new notifications.
                                </div>
                            @endforelse
                        </div>

                        <div class="px-4 py-2 border-t border-slate-100 text-center bg-slate-50 rounded-b-2xl">
                            <a href="{{ route('notifications.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-slate-650 hover:text-luxury-gold transition-colors block">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Unified Portal Link -->
                <a href="{{ Auth::user()->role === 'dealer' ? route('dashboarddealer.dashboard') : (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('inquiries.index')) }}" class="text-sm font-semibold text-slate-800 hover:text-luxury-gold transition-colors duration-200">
                    Dashboard
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-800 hover:text-luxury-gold transition-colors duration-200">
                    Dealer Portal
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold hover:text-white transition-all duration-300 shadow-md">
                    Register
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Toggle Button -->
        <button type="button" 
                class="md:hidden text-slate-800 hover:text-luxury-gold focus:outline-none"
                @click="mobileMenuOpen = !mobileMenuOpen">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-show="!mobileMenuOpen">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-show="mobileMenuOpen" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white border-b border-slate-200/80 px-6 py-4 space-y-4 absolute top-20 left-0 right-0 shadow-lg"
         style="display: none;">
        
        <a href="/" class="block text-sm font-semibold text-slate-800 hover:text-luxury-gold py-2">
            Home
        </a>
        <a href="/cars/search" class="block text-sm font-semibold text-slate-800 hover:text-luxury-gold py-2">
            Search Inventory
        </a>
        
        <hr class="border-slate-100">
        
        @auth
            <a href="{{ Auth::user()->role === 'dealer' ? route('dashboarddealer.dashboard') : (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('inquiries.index')) }}" class="block text-sm font-semibold text-slate-800 hover:text-luxury-gold py-2">
                Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="block w-full text-left text-sm font-semibold text-red-600 hover:text-red-800 py-2">
                    Sign Out
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block text-sm font-semibold text-slate-800 hover:text-luxury-gold py-2">
                Dealer Portal
            </a>
            <a href="{{ route('register') }}" class="block text-center text-sm font-semibold uppercase tracking-wider bg-luxury-charcoal text-white py-3 rounded-lg hover:bg-luxury-gold transition-colors duration-200">
                Register
            </a>
        @endauth
    </div>
</nav>
