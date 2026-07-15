<x-layout.dashboard title="Showrooms Management | Alaa Motors" header="Portal Showrooms Directory">
    
    <div class="space-y-8">
        
        <!-- Filters & Search block -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('admin.showrooms') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                
                <!-- Search bar -->
                <div class="sm:col-span-2">
                    <x-forms.input name="search" 
                                   label="Search Showrooms" 
                                   placeholder="Search by showroom name, location, phone, or owner..." 
                                   value="{{ request('search') }}" />
                </div>

                <!-- Status Filter -->
                <div>
                    <x-forms.select name="status" label="Showroom Status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </x-forms.select>
                </div>

                <!-- Action Button Grid -->
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.showrooms') }}" class="px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        Clear Filters
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                        Filter
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
            @if($showrooms->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-18v18M2.25 5.25h19.5" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Showrooms Found</p>
                    <p class="text-xs text-slate-500 mt-1">There are no showrooms matching your search or filter configuration.</p>
                </div>
            @else
                <x-tables.main :headers="['Showroom Details', 'Owner Info', 'Fleet Size', 'Location & Contact', 'Status', 'Action']">
                    @foreach($showrooms as $showroom)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Showroom Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($showroom->logo)
                                        <img src="{{ asset('storage/' . $showroom->logo) }}" alt="{{ $showroom->name }} Logo" class="h-9 w-9 rounded-full object-cover border border-slate-100 shrink-0">
                                    @else
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-luxury-gold text-white font-bold text-xs uppercase select-none shrink-0">
                                            {{ substr($showroom->name, 0, 1) }}
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-slate-800 truncate select-all">{{ $showroom->name }}</div>
                                        @if($showroom->description)
                                            <div class="text-[11px] text-slate-450 truncate max-w-[240px]" title="{{ $showroom->description }}">{{ $showroom->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Owner Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($showroom->user)
                                    <div class="text-xs font-bold text-slate-800 select-all">{{ $showroom->user->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold mt-0.5 select-all">{{ $showroom->user->email }}</div>
                                @else
                                    <span class="text-xs text-slate-400 italic">No Owner Assigned</span>
                                @endif
                            </td>

                            <!-- Fleet Size (Cars count) -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-800 rounded-full border border-slate-200">
                                    {{ $showroom->cars_count }} cars
                                </span>
                            </td>

                            <!-- Location & Contact -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <div class="text-slate-700 select-all font-medium">{{ $showroom->location ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400 font-semibold mt-0.5 select-all">{{ $showroom->phone ?? 'N/A' }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($showroom->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold tracking-wider uppercase border bg-emerald-50 text-emerald-700 border-emerald-200/80">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold tracking-wider uppercase border bg-slate-100 text-slate-500 border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Change Status Toggle Action -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.showrooms.status', $showroom->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $showroom->is_active ? 0 : 1 }}">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-lg transition-colors border {{ $showroom->is_active ? 'bg-rose-50 border-rose-200 hover:bg-rose-600 hover:border-rose-600 text-rose-700 hover:text-white' : 'bg-emerald-50 border-emerald-200 hover:bg-emerald-600 hover:border-emerald-600 text-emerald-700 hover:text-white' }}">
                                        {{ $showroom->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </x-tables.main>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $showrooms->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layout.dashboard>
