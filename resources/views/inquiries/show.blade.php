<x-layout.dashboard title="Chat Workspace: {{ $inquiry->subject ?? 'Car Inquiry' }} | Alaa Motors" header="Chat Workspace">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Column: Vehicle & Participant Info -->
        <div class="space-y-6">
            <!-- Vehicle Card -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <span class="text-[10px] font-bold text-luxury-gold uppercase tracking-wider block">Inquiry Vehicle Reference</span>
                @if ($inquiry->car)
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden bg-slate-50 border border-slate-100">
                        @if ($inquiry->car->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $inquiry->car->images->first()->path) }}" alt="{{ $inquiry->car->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900 select-all">{{ $inquiry->car->title }}</h4>
                        <p class="text-xs text-slate-400 font-semibold uppercase mt-0.5 select-all">Brand: {{ $inquiry->car->brand }} | Model: {{ $inquiry->car->model }}</p>
                        <p class="text-sm font-extrabold text-slate-900 mt-2 select-all">${{ number_format($inquiry->car->price) }}</p>
                    </div>
                @else
                    <div class="py-4 text-center text-xs text-slate-400">This vehicle listing is no longer available.</div>
                @endif
            </div>

            <!-- Participant metadata card -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <span class="text-[10px] font-bold text-luxury-gold uppercase tracking-wider block">Participant Details</span>
                
                @php
                    $isDealer = Auth::id() === $inquiry->dealer_id;
                    $counterpart = $isDealer ? $inquiry->buyer : $inquiry->dealer;
                @endphp

                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-luxury-charcoal text-white font-bold text-xs uppercase select-none">
                        {{ substr($counterpart->name ?? 'U', 0, 1) }}
                    </span>
                    <div>
                        <div class="text-sm font-bold text-slate-800 select-all">{{ $counterpart->name ?? 'Counterpart' }}</div>
                        <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">
                            Role: {{ $counterpart->role ?? 'User' }}
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="space-y-2 text-xs font-medium text-slate-600 select-all">
                    <div>Email: {{ $counterpart->email ?? 'N/A' }}</div>
                    <div>Status: {{ ucfirst($inquiry->status) }}</div>
                </div>

                @if($inquiry->status !== 'closed')
                    <hr class="border-slate-100">
                    <form action="{{ route('inquiries.close', $inquiry->id) }}" method="POST" class="w-full pt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center h-10 rounded-xl text-xs font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-600 hover:text-white transition-all duration-200">
                            Close Inquiry
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right Column: Interactive Chat Box -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl shadow-sm flex flex-col h-[70vh] min-h-[500px]">
            
            <!-- Chat Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider select-all">{{ $inquiry->subject ?? 'General Chat' }}</h3>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5">Ticket ID: #{{ $inquiry->id }}</p>
                </div>
                <x-badges.status :status="$inquiry->status" />
            </div>

            <!-- Messages Stream Area -->
            <div class="flex-grow overflow-y-auto p-6 space-y-4 bg-slate-50/50" id="chat-messages-container">
                @foreach ($inquiry->messages as $msg)
                    @php
                        $myMessage = $msg->sender_id === Auth::id();
                    @endphp

                    <div class="flex flex-col {{ $myMessage ? 'items-end' : 'items-start' }}">
                        <!-- Message Bubble -->
                        <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-relaxed shadow-sm border {{ $myMessage ? 'bg-luxury-charcoal text-white border-luxury-charcoal rounded-tr-none' : 'bg-white text-slate-800 border-slate-200/80 rounded-tl-none' }} select-text">
                            {{ $msg->message }}
                        </div>
                        
                        <!-- Sender & Timestamp -->
                        <div class="flex items-center gap-1.5 mt-1.5 px-1 text-[10px] text-slate-400 font-medium select-none">
                            <span class="font-bold uppercase tracking-wider">{{ $myMessage ? 'You' : ($msg->sender->name ?? 'User') }}</span>
                            <span>&bull;</span>
                            <span>{{ $msg->created_at ? $msg->created_at->diffForHumans() : 'N/A' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form Box -->
            @if ($inquiry->status !== 'closed')
                <div class="p-6 border-t border-slate-100 bg-white rounded-b-3xl">
                    <form action="{{ route('inquiries.message', $inquiry->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <x-forms.textarea name="message" 
                                                  placeholder="Type your reply here..." 
                                                  required 
                                                  rows="2" />
                            </div>
                            <button type="submit" class="h-11 px-5 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md shrink-0">
                                Reply
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-6 border-t border-slate-100 bg-slate-50 text-center rounded-b-3xl text-xs text-slate-400 font-semibold select-none">
                    This inquiry has been closed and is read-only.
                </div>
            @endif

        </div>

    </div>

    <!-- Alpine.js Auto-scroll to Chat Bottom script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('chat-messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</x-layout.dashboard>
