<x-layout.dashboard title="Notifications Workspace | Alaa Motors" header="Notifications Workspace">
    
    <div class="max-w-2xl bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Notifications</h2>
                <p class="text-xs text-slate-500 mt-1">Review activity, inquiries, and booking request alerts.</p>
            </div>
            @if (Auth::user()->unreadNotifications()->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold uppercase tracking-wider text-slate-650 hover:bg-slate-50 transition-colors shadow-sm">
                        Mark All Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Session Status Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($notifications->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                </svg>
                <p class="text-sm font-semibold text-slate-700">Inbox is Empty</p>
                <p class="text-xs text-slate-500 mt-1">You have no activity notifications yet.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($notifications as $noti)
                    @php
                        $isUnread = is_null($noti->read_at);
                    @endphp
                    <div class="py-4 flex justify-between items-start gap-4 transition-colors {{ $isUnread ? 'bg-slate-50/40 -mx-6 px-6 rounded-xl' : '' }}">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-800 truncate select-all">{{ $noti->data['title'] ?? 'Notification' }}</h3>
                                @if ($isUnread)
                                    <span class="h-2 w-2 rounded-full bg-rose-600 shrink-0" title="Unread"></span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-550 leading-relaxed select-text">{{ $noti->data['message'] ?? '' }}</p>
                            <span class="text-[10px] text-slate-400 block font-medium">{{ $noti->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="shrink-0 flex gap-2">
                            @if ($isUnread)
                                <form action="{{ route('notifications.read', $noti->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[10px] font-extrabold uppercase tracking-wider text-luxury-gold hover:bg-slate-50 transition-colors shadow-sm">
                                        Mark Read & Go
                                    </button>
                                </form>
                            @else
                                @if (isset($noti->data['url']))
                                    <a href="{{ $noti->data['url'] }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[10px] font-extrabold uppercase tracking-wider text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
                                        View Details
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>
</x-layout.dashboard>
