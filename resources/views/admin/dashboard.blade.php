<x-layout.dashboard title="Admin Overview Dashboard | Alaa Motors" header="Admin Control Panel">
    
    <div class="space-y-8">
        
        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <x-cards.stats label="Total Users" :value="$totalUsers" />
            <x-cards.stats label="Registered Dealers" :value="$totalDealers" />
            <x-cards.stats label="Active Listings" :value="$totalCars" />
            <x-cards.stats label="Showrooms" :value="$totalShowrooms" />
            <x-cards.stats label="Global Inquiries" :value="$totalInquiries" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Latest Listings -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-800">Latest Showroom Listings</h3>
                    <a href="{{ route('admin.cars') }}" class="text-[10px] font-bold text-luxury-gold uppercase tracking-wider hover:text-luxury-gold-hover">
                        View All
                    </a>
                </div>

                @if($recentCars->isEmpty())
                    <div class="py-12 text-center text-xs text-slate-400">No vehicles listed yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="text-slate-400 font-bold uppercase tracking-wider border-b border-slate-150">
                                    <th class="py-3 pr-4">Vehicle</th>
                                    <th class="py-3 px-4">Dealer</th>
                                    <th class="py-3 px-4">Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentCars as $car)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3.5 pr-4">
                                            <div class="font-bold text-slate-800 select-all">{{ $car->title }}</div>
                                            <div class="text-[10px] text-slate-450 uppercase mt-0.5 select-all">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</div>
                                        </td>
                                        <td class="py-3.5 px-4 font-medium text-slate-600 select-all">
                                            {{ $car->user->name ?? 'System User' }}
                                        </td>
                                        <td class="py-3.5 px-4 font-extrabold text-slate-800 select-all">
                                            ${{ number_format($car->price) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Recent Inquiries -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-800">Recent Customer Inquiries</h3>
                    <a href="{{ route('admin.inquiries') }}" class="text-[10px] font-bold text-luxury-gold uppercase tracking-wider hover:text-luxury-gold-hover">
                        View All
                    </a>
                </div>

                @if($recentInquiries->isEmpty())
                    <div class="py-12 text-center text-xs text-slate-400">No buyer inquiries received yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="text-slate-400 font-bold uppercase tracking-wider border-b border-slate-150">
                                    <th class="py-3 pr-4">Car Ref</th>
                                    <th class="py-3 px-4">Sender</th>
                                    <th class="py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentInquiries as $inquiry)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3.5 pr-4">
                                            <div class="font-bold text-slate-800 select-all">{{ $inquiry->car->title ?? 'Deleted Car' }}</div>
                                            <div class="text-[10px] text-slate-450 uppercase mt-0.5 select-all">{{ $inquiry->subject ?? 'Inquiry' }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 font-medium text-slate-600 select-all">
                                            {{ $inquiry->buyer->name ?? 'Verified Buyer' }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <x-badges.status :status="$inquiry->status" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout.dashboard>
