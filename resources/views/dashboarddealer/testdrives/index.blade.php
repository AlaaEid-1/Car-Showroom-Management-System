<x-layout.dashboard title="Received Test Drives | Alaa Motors" header="Manage Test Drives">
    
    <div class="space-y-8">
        
        <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Received Booking Requests</h2>
                <p class="text-xs text-slate-500 mt-1">Review, approve, or reject customer test drive schedules.</p>
            </div>

            <!-- Session Status Notifications -->
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($testDrives->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Booking Requests</p>
                    <p class="text-xs text-slate-500 mt-1">No test drive requests have been made for your vehicles yet.</p>
                </div>
            @else
                <x-tables.main :headers="['Vehicle Reference', 'Customer Details', 'Preferred Schedule', 'Notes', 'Current Status', 'Quick Actions']">
                    @foreach ($testDrives as $td)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Vehicle -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        @if ($td->car && $td->car->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $td->car->images->first()->path) }}" alt="{{ $td->car->title }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 select-all">{{ $td->car->title ?? 'Deleted Car' }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">{{ $td->car->brand ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-slate-700 font-bold select-all">{{ $td->user->name ?? 'Deleted User' }}</div>
                                <div class="text-[10px] text-slate-450 mt-0.5 select-all">{{ $td->user->email ?? 'N/A' }}</div>
                            </td>

                            <!-- Date/Time -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-700 font-semibold select-all">
                                {{ \Carbon\Carbon::parse($td->scheduled_at)->format('Y-m-d H:i') }}
                            </td>

                            <!-- Notes -->
                            <td class="px-6 py-4 max-w-[150px] truncate text-xs text-slate-500 font-medium select-text" title="{{ $td->notes }}">
                                {{ $td->notes ?? '-' }}
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border
                                    @if ($td->status === 'pending') bg-yellow-50 text-yellow-700 border-yellow-200
                                    @elseif ($td->status === 'approved') bg-blue-50 text-blue-700 border-blue-200
                                    @elseif ($td->status === 'rejected') bg-red-50 text-red-700 border-red-200
                                    @elseif ($td->status === 'completed') bg-green-50 text-green-700 border-green-200
                                    @endif">
                                    {{ $td->status }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold uppercase tracking-wider space-x-2">
                                @if ($td->status === 'pending')
                                    <!-- Approve Form -->
                                    <form action="{{ route('dashboarddealer.test-drives.update', $td->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 transition-colors">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('dashboarddealer.test-drives.update', $td->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="text-red-650 hover:text-red-800 transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                @elseif ($td->status === 'approved')
                                    <!-- Complete Form -->
                                    <form action="{{ route('dashboarddealer.test-drives.update', $td->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 transition-colors">
                                            Mark Completed
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-tables.main>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $testDrives->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layout.dashboard>
