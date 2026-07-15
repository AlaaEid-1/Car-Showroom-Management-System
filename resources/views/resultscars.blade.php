<x-layout.app title="Fleet Inventory | Alaa Motors" description="Search and filter our premium collection of luxury vehicles. Finding your dream performance car is just a click away.">
    
    <div class="bg-slate-50 border-b border-slate-200/50 py-10">
        <div class="max-w-7xl mx-auto px-6">
            <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block mb-1">
                Showroom Inventory
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Search Fleet
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 items-start">
            
            <!-- Filters Sidebar Pane -->
            <aside class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm sticky top-24">
                <h3 class="text-sm font-bold tracking-wider text-slate-900 uppercase mb-6 flex items-center gap-2">
                    <svg class="h-4.5 w-4.5 text-luxury-gold" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.59l-5.34 5.34a2.25 2.25 0 0 0-.659 1.59v3.42a2.25 2.25 0 0 1-.62 1.537l-2.03 2.03a.75.75 0 0 1-1.28-.53v-6.457a2.25 2.25 0 0 0-.659-1.59l-5.34-5.34A2.25 2.25 0 0 1 2.25 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Refine Search
                </h3>

                <form action="{{ route('cars.search') }}" method="GET" class="space-y-6">
                    <!-- Model Input -->
                    <div>
                        <x-forms.input name="model" label="Vehicle Model" placeholder="e.g. Corolla" value="{{ request('model') }}" />
                    </div>

                    <!-- Year Input -->
                    <div>
                        <x-forms.input name="year" label="Production Year" placeholder="e.g. 2022" type="number" value="{{ request('year') }}" />
                    </div>

                    <!-- Price Select -->
                    <div>
                        <x-forms.select name="price_range" label="Value Range">
                            <option value="">Any Price</option>
                            <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>$50k - $100k</option>
                            <option value="100-250" {{ request('price_range') == '100-250' ? 'selected' : '' }}>$100k - $250k</option>
                            <option value="250-500" {{ request('price_range') == '250-500' ? 'selected' : '' }}>$250k - $500k</option>
                            <option value="500+" {{ request('price_range') == '500+' ? 'selected' : '' }}>$500k+</option>
                        </x-forms.select>
                    </div>

                    <!-- Form Buttons -->
                    <div class="pt-4 space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 h-11 rounded-xl text-xs font-semibold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-all duration-300">
                            Apply Filters
                        </button>
                        
                        <a href="{{ route('cars.search') }}" class="w-full flex items-center justify-center h-11 rounded-xl text-xs font-semibold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                            Clear All
                        </a>
                    </div>
                </form>
            </aside>

            <!-- Results Listing Grid -->
            <div class="lg:col-span-3 space-y-10">
                @if ($cars->isEmpty())
                    <!-- Empty Results State -->
                    <div class="bg-white border border-slate-200/60 rounded-2xl p-16 text-center text-slate-500 shadow-sm flex flex-col items-center justify-center">
                        <svg class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.09-1.451M17.25 18.75h-3m3 0a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 18.507 4.5h.386a1.125 1.125 0 0 1 1.12 1.243l-1.264 12A1.125 1.125 0 0 1 17.25 18.75Z" />
                        </svg>
                        <h3 class="text-lg font-bold text-slate-800">No Vehicles Found</h3>
                        <p class="text-sm mt-1 text-slate-400 max-w-sm leading-relaxed">
                            No vehicles matching your current selection criteria are available. Adjust your filters or explore other models.
                        </p>
                    </div>
                @else
                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($cars as $car)
                            <x-car-card :car="$car" />
                        @endforeach
                    </div>

                    <!-- Custom Styled Pagination -->
                    <div class="pt-6 border-t border-slate-200/50">
                        {{ $cars->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layout.app>
