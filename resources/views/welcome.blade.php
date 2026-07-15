<x-layout.app title="Alaa Motors | Luxury Automotive Showroom" description="Experience the pinnacle of automotive craftsmanship. Browse and search our exclusive inventory of sports and luxury vehicles.">
    
    <!-- Hero Section -->
    <div class="relative bg-luxury-charcoal h-[85vh] min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            @if(file_exists(public_path('storage/cars/hero.jpg')))
                <img src="{{ asset('storage/cars/hero.jpg') }}" alt="Luxury sports car" class="w-full h-full object-cover opacity-35 object-center scale-105 animate-[pulse_10s_infinite]">
            @else
                <!-- Gradient mesh fallback -->
                <div class="w-full h-full bg-gradient-to-tr from-slate-950 via-slate-900 to-luxury-charcoal opacity-80"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-luxury-charcoal via-transparent to-black/30"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white space-y-6">
            <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block animate-fade-in">
                Exotic & Luxury Automotives
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight select-none">
                Define Your Journey.<br>
                Own The Performance.
            </h1>
            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto font-medium">
                Curating the world's most exceptional automotive designs, engineering marvels, and bespoke showroom experiences.
            </p>
        </div>
    </div>

    <!-- Search Section (Floating styled filter) -->
    <div class="max-w-6xl mx-auto px-6 -mt-16 relative z-20">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100">
            <form action="{{ route('cars.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                
                <!-- Model Filter -->
                <div>
                    <x-forms.select name="model" label="Select Model">
                        <option value="">All Models</option>
                        @foreach ($models as $m)
                            <option value="{{ $m }}" {{ request('model') == $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </x-forms.select>
                </div>

                <!-- Year Filter -->
                <div>
                    <x-forms.select name="year" label="Production Year">
                        <option value="">Any Year</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </x-forms.select>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <x-forms.select name="price_range" label="Value Range">
                        <option value="">Any Price</option>
                        <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>$50k - $100k</option>
                        <option value="100-250" {{ request('price_range') == '100-250' ? 'selected' : '' }}>$100k - $250k</option>
                        <option value="250-500" {{ request('price_range') == '250-500' ? 'selected' : '' }}>$250k - $500k</option>
                        <option value="500+" {{ request('price_range') == '500+' ? 'selected' : '' }}>$500k+</option>
                    </x-forms.select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 h-[46px] rounded-xl text-sm font-semibold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-all duration-300 shadow-md">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                        </svg>
                        Search Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Explore Fleet Banner -->
    <section class="max-w-7xl mx-auto px-6 py-24" id="explore">
        <div class="bg-luxury-charcoal text-white rounded-3xl p-8 sm:p-16 relative overflow-hidden shadow-xl border border-slate-800">
            <div class="relative z-10 max-w-2xl space-y-6">
                <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block">
                    Exclusive Selection
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Discover Our Curated Inventory
                </h2>
                <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                    Browse our full collection of premium sports cars, luxury electric vehicles, and executive sedans. Filter by year, model, and value to find your perfect vehicle.
                </p>
                <div class="pt-2">
                    <a href="{{ route('cars.search') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-gold text-white hover:bg-white hover:text-luxury-charcoal transition-all duration-300">
                        View Full Fleet
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Showcase Section -->
    <section class="bg-slate-100 border-y border-slate-200/50 py-20" id="brands">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase block mb-2">
                Curating Global Luxury
            </span>
            <h2 class="text-2xl font-bold tracking-tight text-slate-800 mb-10">
                Exceptional Curation Standards
            </h2>
            
            <div class="flex flex-wrap items-center justify-center gap-12 sm:gap-20 opacity-60">
                <span class="text-lg font-bold tracking-widest text-slate-650 uppercase">Porsche</span>
                <span class="text-lg font-bold tracking-widest text-slate-650 uppercase">BMW</span>
                <span class="text-lg font-bold tracking-widest text-slate-650 uppercase">Mercedes</span>
                <span class="text-lg font-bold tracking-widest text-slate-650 uppercase">Tesla</span>
                <span class="text-lg font-bold tracking-widest text-slate-650 uppercase">Toyota</span>
                <span class="text-lg font-bold tracking-widest text-slate-650 uppercase">Honda</span>
            </div>
        </div>
    </section>

    <!-- Marketing Column / Showroom benefits -->
    <section class="max-w-7xl mx-auto px-6 py-24 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block">
                Bespoke Experiences
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Designed for Connoisseurs of Automotive Artistry
            </h2>
            <p class="text-slate-600 leading-relaxed">
                Whether inspecting the hand-stitched leather contours or testing the battery efficiency metrics, our showrooms provide complete transparency and concierge care.
            </p>
            <div class="grid grid-cols-2 gap-6 pt-4">
                <div>
                    <h4 class="font-bold text-slate-900">Elite Certification</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Multi-point inspections completed by master technicians.</p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Seamless Transactions</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Direct inquiries and quick digital scheduling portals.</p>
                </div>
            </div>
        </div>
        
        <div class="relative rounded-3xl overflow-hidden shadow-2xl aspect-[4/3] bg-slate-900">
            @if(file_exists(public_path('storage/cars/hero.jpg')))
                <img src="{{ asset('storage/cars/hero.jpg') }}" alt="Showroom interior" class="w-full h-full object-cover opacity-75">
            @endif
            <div class="absolute inset-0 bg-gradient-to-tr from-luxury-charcoal/80 to-transparent"></div>
            <div class="absolute bottom-8 left-8 text-white space-y-1">
                <p class="text-xs font-bold tracking-widest text-luxury-gold uppercase">Gaza, Palestine</p>
                <p class="text-lg font-bold">Alaa Motors Showroom Portal</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-luxury-charcoal text-white py-20 border-b border-slate-800 text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Are You a Certified Dealer?</h2>
            <p class="text-slate-400 max-w-xl mx-auto text-sm leading-relaxed">
                Join our premium network of luxury showrooms. List your inventory, connect with verified buyers, and receive customer inquiries securely.
            </p>
            <div class="pt-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider bg-luxury-gold text-white hover:bg-white hover:text-luxury-charcoal transition-all duration-300 shadow-lg">
                    Register Showroom
                </a>
            </div>
        </div>
    </section>
</x-layout.app>
