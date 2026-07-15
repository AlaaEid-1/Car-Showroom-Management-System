<x-layout.dashboard title="Inquiries Workspace | Alaa Motors" header="Inquiries Workspace">
    
    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Conversations & Offers</h2>
            <p class="text-xs text-slate-500 mt-1">Review received inquiries from verified buyers and discuss negotiations.</p>
        </div>

        @if ($inquiries->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                <p class="text-sm font-semibold text-slate-700">No Inquiries Available</p>
                <p class="text-xs text-slate-500 mt-1">You have not received any inquiries or negotiations yet.</p>
            </div>
        @else
            <!-- Table listing -->
            <x-tables.main :headers="['Car Detail', 'Sender (Buyer)', 'Subject', 'Last Response', 'Status', 'Actions']">
                @foreach ($inquiries as $inquiry)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <!-- Car details -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                    @if ($inquiry->car && $inquiry->car->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $inquiry->car->images->first()->path) }}" alt="{{ $inquiry->car->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-300">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800 select-all">{{ $inquiry->car->title ?? 'Deleted Car' }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">Brand: {{ $inquiry->car->brand ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Sender / Buyer details -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-800 select-all">{{ $inquiry->buyer->name ?? 'Buyer User' }}</div>
                            <div class="text-xs text-slate-400 mt-0.5 select-all">{{ $inquiry->buyer->email ?? 'N/A' }}</div>
                        </td>

                        <!-- Subject -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider select-all">
                                {{ $inquiry->subject ?? 'General Inquiry' }}
                            </span>
                        </td>

                        <!-- Last response date -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-medium text-slate-600 select-all">
                                {{ $inquiry->last_message_at ? $inquiry->last_message_at->diffForHumans() : 'N/A' }}
                            </div>
                        </td>

                        <!-- Status badge -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badges.status :status="$inquiry->status" />
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('inquiries.show', $inquiry->id) }}" class="px-3.5 py-2 text-xs font-bold uppercase tracking-wider bg-slate-150 text-slate-700 hover:bg-luxury-gold hover:text-white rounded-xl transition-colors">
                                View Chat
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-tables.main>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</x-layout.dashboard>
