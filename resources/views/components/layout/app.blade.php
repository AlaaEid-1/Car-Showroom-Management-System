<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Premium Motors | Luxury Car Showroom' }}</title>

    <!-- Meta Description for SEO -->
    <meta name="description" content="{{ $description ?? 'Discover our curated collection of premium luxury vehicles. Porsche, Tesla, BMW, and more, available at our modern showrooms.' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Style and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-luxury-cream/50 min-h-screen flex flex-col selection:bg-luxury-gold selection:text-white">
    <!-- Main Header / Navbar Component -->
    <x-navbar.main />

    <!-- Main Content Area -->
    <main class="flex-grow pt-20">
        {{ $slot }}
    </main>

    <!-- Global Footer -->
    <footer class="bg-luxury-charcoal text-slate-400 py-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Brand Column -->
            <div class="space-y-4">
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-xl font-bold tracking-widest text-white uppercase">
                        ALAA<span class="text-luxury-gold">MOTORS</span>
                    </span>
                </a>
                <p class="text-sm leading-relaxed">
                    Setting the global benchmark for modern luxury automotive showrooms and curation.
                </p>
            </div>

            <!-- Explore Column -->
            <div>
                <h4 class="text-sm font-semibold tracking-wider text-white uppercase mb-4">Explore</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/cars/search" class="hover:text-luxury-gold transition-colors duration-200">Search Vehicles</a></li>
                    <li><a href="/#featured" class="hover:text-luxury-gold transition-colors duration-200">Featured Listings</a></li>
                    <li><a href="/#brands" class="hover:text-luxury-gold transition-colors duration-200">Popular Brands</a></li>
                </ul>
            </div>

            <!-- Portals Column -->
            <div>
                <h4 class="text-sm font-semibold tracking-wider text-white uppercase mb-4">Portals</h4>
                <ul class="space-y-2 text-sm">
                    @auth
                        <li>
                            <a href="{{ route('inquiries.index') }}" class="hover:text-luxury-gold transition-colors duration-200">
                                My Workspace
                            </a>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-luxury-gold transition-colors duration-200">Dealer Sign In</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-luxury-gold transition-colors duration-200">Register</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Legal Column -->
            <div>
                <h4 class="text-sm font-semibold tracking-wider text-white uppercase mb-4">Contact</h4>
                <p class="text-sm leading-relaxed mb-2">
                    Gaza & Ramallah Showrooms
                </p>
                <p class="text-sm font-medium text-white">
                    support@alaamotors.com
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 mt-16 pt-8 border-t border-slate-800/60 flex flex-col md:flex-row justify-between items-center text-xs space-y-4 md:space-y-0">
            <p>&copy; {{ date('Y') }} Alaa Motors Marketplace. All rights reserved.</p>
            <p class="tracking-wide">DESIGNED FOR PREMIUM PERFORMANCE</p>
        </div>
    </footer>
</body>
</html>
