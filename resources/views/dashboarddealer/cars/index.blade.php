<x-layout.dashboard title="My Fleet Directory | Alaa Motors" header="Showroom Fleet Inventory">

    <div class="space-y-8">

        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-cards.stats label="Total Cars Fleet" :value="$totalCars" />
            <x-cards.stats label="Active Listings" :value="$activeListings" />
            <x-cards.stats label="New Messages" :value="$newMessages" />
            <x-cards.stats label="Test Drive Requests" :value="$testDriveRequests" />
        </div>

        <!-- Inventory Filters & Actions Bar -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

                <!-- Status Tabs -->
                <nav class="flex border-b border-slate-100 w-full sm:w-auto">
                    @foreach ($status_options as $opt)
                        @php
                            $optSlug = strtolower($opt['name']);
                            $isActive = $status === $optSlug;
                        @endphp
                        <a href="{{ route('dashboarddealer.cars.index', ['status' => $optSlug, 'search' => request('search')]) }}"
                           class="px-4 py-3 text-xs font-semibold uppercase tracking-wider border-b-2 transition-colors duration-200 -mb-px flex items-center gap-2 {{ $isActive ? 'border-luxury-gold text-slate-900 font-bold' : 'border-transparent text-slate-400 hover:text-slate-700' }}">
                            {{ $opt['name'] }}
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $isActive ? 'bg-luxury-gold/15 text-luxury-gold' : 'bg-slate-100 text-slate-400' }}">
                                {{ $opt['count'] }}
                            </span>
                        </a>
                    @endforeach
                </nav>

                <!-- Add vehicle link -->
                <a href="{{ route('dashboarddealer.cars.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-md">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Listing
                </a>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('dashboarddealer.cars.index') }}" method="GET" class="flex gap-3">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by brand, model, or title..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-luxury-gold focus:border-luxury-gold outline-none">
                </div>
                @if(request()->filled('search'))
                    <a href="{{ route('dashboarddealer.cars.index', ['status' => $status]) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-colors flex items-center justify-center">
                        Clear
                    </a>
                @endif
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-sm shrink-0">
                    Search
                </button>
            </form>

            <!-- Table Container -->
            @if ($cars->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 18.507 4.5h.386a1.125 1.125 0 0 1 1.12 1.243l-1.264 12A1.125 1.125 0 0 1 17.25 18.75Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No cars found</p>
                    <p class="text-xs text-slate-500 mt-1">There are no listings matching the "{{ ucfirst($status) }}" filter.</p>
                </div>
            @else
                <x-tables.main :headers="['Cover', 'Details', 'Model Info', 'Value', 'Status', 'Actions']">
                    @foreach ($cars as $car)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Cover Thumbnail -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-12 w-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                    @if ($car->images->isNotEmpty())
                                        <img src="{{ url('storage/' . ltrim($car->images->first()->path, '/')) }}"
                                            alt="{{ $car->title }}"
                                            class="h-full w-full object-cover">                                    @else
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

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badges.status :status="$car->status" />
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    @if ($status === 'trash')
                                        <!-- Restore Form -->
                                        <form action="{{ route('dashboarddealer.cars.restore', $car->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-slate-100 text-slate-700 hover:bg-luxury-gold hover:text-white rounded-lg transition-colors">
                                                Restore
                                            </button>
                                        </form>

                                        <!-- Permanent Force Delete Form -->
                                        <form action="{{ route('dashboarddealer.cars.forceDelete', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this vehicle? This action cannot be undone!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-colors">
                                                Purge
                                            </button>
                                        </form>
                                    @else
                                        <!-- Preview -->
                                        <a href="{{ route('dashboarddealer.cars.show', $car->id) }}" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-slate-150 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                            Preview
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('dashboarddealer.cars.edit', $car->id) }}" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-slate-150 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                            Edit
                                        </a>

                                        <!-- Delete to Trash -->
                                        <form action="{{ route('dashboarddealer.cars.destroy', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Move this vehicle to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-colors">
                                                Trash
                                            </button>
                                        </form>
                                    @endif
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
