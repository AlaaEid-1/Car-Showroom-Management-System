<x-layout.dashboard title="Cars Directory | Alaa Motors" header="Portal Fleet Directory">
    
    <div class="space-y-8">
        
        <!-- Filters & Search block -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('admin.cars') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                
                <!-- Search bar -->
                <div class="sm:col-span-2">
                    <x-forms.input name="search" 
                                   label="Search Fleet" 
                                   placeholder="Search by title, brand, or model designation..." 
                                   value="{{ request('search') }}" />
                </div>

                <!-- Status Filter -->
                <div>
                    <x-forms.select name="status" label="Listing Status">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Active)</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                        <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                    </x-forms.select>
                </div>

                <!-- Action Button Grid -->
                <div class="sm:col-span-3 flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.cars') }}" class="px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        Clear Filters
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                        Filter Fleet
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

        <!-- Cars Table -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            @if($cars->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 18.507 4.5h.386a1.125 1.125 0 0 1 1.12 1.243l-1.264 12A1.125 1.125 0 0 1 17.25 18.75Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Listings Found</p>
                    <p class="text-xs text-slate-500 mt-1">There are no vehicles matching your search parameters.</p>
                </div>
            @else
                <x-tables.main :headers="['Cover', 'Details', 'Model Info', 'Value', 'Dealer Owner', 'Status', 'Actions']">
                    @foreach ($cars as $car)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Cover Image -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-12 w-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                    @if ($car->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $car->images->first()->path) }}" alt="{{ $car->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-300">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-800 select-all">{{ $car->title }}</div>
                                <div class="text-xs text-slate-400 font-semibold uppercase mt-0.5 select-all">{{ $car->brand }}</div>
                            </td>

                            <!-- Model Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-700 select-all">{{ $car->model }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 select-all">Year: {{ $car->year }}</div>
                            </td>

                            <!-- Value -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-extrabold text-slate-900 select-all">
                                    ${{ number_format($car->price) }}
                                </div>
                            </td>

                            <!-- Owner / Dealer -->
                            <td class="px-6 py-4 whitespace-nowrap select-all">
                                <div class="text-xs font-bold text-slate-800">{{ $car->user->name ?? 'System User' }}</div>
                                <div class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">{{ $car->user->username ?? 'dealer' }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badges.status :status="$car->status" />
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <!-- View Details link -->
                                    <a href="{{ route('cars.show', $car->id) }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-slate-150 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                        View
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.cars.delete', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this car listing from showroom indexes? (This soft-deletes the record)')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </x-tables.main>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $cars->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layout.dashboard>
