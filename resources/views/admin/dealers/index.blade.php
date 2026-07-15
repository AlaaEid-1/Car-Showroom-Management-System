<x-layout.dashboard title="Dealers Directory | Alaa Motors" header="Portal Dealers Directory">
    
    <div class="space-y-8">
        
        <!-- Header Actions & Navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Showroom Affiliates & Dealers</h2>
                <p class="text-xs text-slate-500 mt-1">Review active dealer profiles, fleet counts, and modify account states.</p>
            </div>
            
            <a href="{{ route('admin.dealers.requests') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Dealer Requests Console
            </a>
        </div>

        <!-- Filters & Search block -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('admin.dealers') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                
                <!-- Search bar -->
                <div class="sm:col-span-2">
                    <x-forms.input name="search" 
                                   label="Search Dealers" 
                                   placeholder="Search by name, email, or username..." 
                                   value="{{ request('search') }}" />
                </div>

                <!-- Status Filter -->
                <div>
                    <x-forms.select name="status" label="Account Status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Pending Approval)</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </x-forms.select>
                </div>

                <!-- Action Button Grid -->
                <div class="sm:col-span-3 flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.dealers') }}" class="px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        Clear Filters
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                        Filter Directory
                    </button>
                </div>

            </form>
        </div>

        <!-- Success Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Directory Table -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            @if($dealers->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615m-7.5 0V5.25m3.75 4.099V5.25m3.75 4.099V5.25m-.75-3h7.5a.75.75 0 0 1 .75.75V9.05m-7.5-7.5a.75.75 0 0 0-.75.75V9.05m0 0a3.001 3.001 0 0 0 3.75-.615 3.001 3.001 0 0 0 3.75.615m0 0V5.25m-2.25 10.5a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5ZM6 15a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-.75.75h-1.5A.75.75 0 0 1 6 15.75v-1.5Zm11.25-4.5a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5Zm-6-6a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 .75.75h1.5a.75.75 0 0 0 .75-.75v-1.5Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Dealers Found</p>
                    <p class="text-xs text-slate-500 mt-1">There are no dealer accounts matching your search filters.</p>
                </div>
            @else
                <x-tables.main :headers="['Dealer Identity', 'Date Registered', 'Fleet Count', 'Status', 'Change Status']">
                    @foreach($dealers as $dealer)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Identity -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-luxury-charcoal text-white font-bold text-xs uppercase select-none">
                                        {{ substr($dealer->name, 0, 1) }}
                                    </span>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 select-all">{{ $dealer->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">@ {{ $dealer->username }} &bull; {{ $dealer->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Date Registered -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-500 select-all">
                                {{ $dealer->created_at ? $dealer->created_at->format('M d, Y') : 'N/A' }}
                            </td>

                            <!-- Fleet Size -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700 select-all">
                                {{ $dealer->cars_count ?? 0 }} vehicles
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badges.status :status="$dealer->status" />
                            </td>

                            <!-- Status Switcher -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.dealers.status', $dealer->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" 
                                            onchange="this.form.submit()" 
                                            class="rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 focus:border-luxury-gold focus:ring-luxury-gold/25 outline-none cursor-pointer">
                                        <option value="active" {{ $dealer->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $dealer->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ $dealer->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </x-tables.main>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $dealers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layout.dashboard>
