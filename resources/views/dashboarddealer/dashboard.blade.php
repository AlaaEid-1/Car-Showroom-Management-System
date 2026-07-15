<x-layout.dashboard title="Dealer Dashboard | Alaa Motors" header="Dealer Console Overview">
    
    <div class="space-y-8">
        
        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-cards.stats label="My Cars Fleet" :value="$totalCars" />
            <x-cards.stats label="Active Listings" :value="$publishedCars" />
            <x-cards.stats label="Inquiries Received" :value="$inquiriesCount" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Welcome Info -->
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900">Welcome Back, {{ Auth::user()->name }}!</h3>
                <p class="text-sm text-slate-550 leading-relaxed">
                    Access your dealer control panel to manage your luxury vehicle fleet, reply to buyer inquiries, and update your showroom details. Use the sidebar navigation menu to browse different sections of your dealer portal.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('dashboarddealer.cars.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                        Manage Fleet Inventory
                    </a>
                    <a href="{{ route('inquiries.index') }}" class="px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        View Customer Inquiries
                    </a>
                </div>
            </div>

            <!-- Showroom Card -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="pb-2 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">My Showroom</h3>
                    @if($showroom)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-150">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-150">
                            Required
                        </span>
                    @endif
                </div>

                @if($showroom)
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            @if($showroom->logo)
                                <img src="{{ asset('storage/' . $showroom->logo) }}" alt="{{ $showroom->name }} Logo" class="h-11 w-11 rounded-full object-cover border border-slate-100 shrink-0">
                            @else
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-luxury-gold text-white font-bold text-sm uppercase select-none shrink-0">
                                    {{ substr($showroom->name, 0, 1) }}
                                </span>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 truncate select-all">{{ $showroom->name }}</h4>
                                <p class="text-xs text-slate-400 truncate">{{ $showroom->location ?? 'No location added' }}</p>
                            </div>
                        </div>

                        <div class="text-xs space-y-2 text-slate-650 pt-1">
                            @if($showroom->description)
                                <p class="leading-relaxed line-clamp-3 italic">"{{ $showroom->description }}"</p>
                            @endif
                            <div class="flex items-center gap-2 pt-1 font-medium">
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.806-5.194-4.177-7-7l1.3-1.3c.372-.372.502-.914.364-1.423L6.059 4.19a1.25 1.25 0 0 0-1.226-.94H3.375A2.25 2.25 0 0 0 1.125 5.5v1.25Z" />
                                </svg>
                                <span class="select-all">{{ $showroom->phone ?? 'No phone contact' }}</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('dashboarddealer.showroom.edit') }}" class="w-full flex items-center justify-center h-10 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                                Edit Showroom Profile
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6 space-y-4">
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">
                            You have not initialized your dealer showroom yet. Setting up a showroom is required to list cars for sale.
                        </p>
                        <a href="{{ route('dashboarddealer.showroom.edit') }}" class="w-full flex items-center justify-center h-10 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-gold text-white hover:bg-luxury-gold-hover transition-colors duration-200 shadow-sm">
                            Create Showroom Now
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout.dashboard>
