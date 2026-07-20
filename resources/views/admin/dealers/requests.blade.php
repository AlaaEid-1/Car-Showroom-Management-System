<x-layout.dashboard title="Pending Approvals | Alaa Motors" header="Dealer Registration Requests">
    
    <div class="space-y-8">
        
        <!-- Header Actions & Navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Pending Showroom Affiliations</h2>
                <p class="text-xs text-slate-500 mt-1">Review new dealer registration applications and approve or reject access.</p>
            </div>
            
            <a href="{{ route('admin.dealers') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 uppercase tracking-wider transition-colors">
                Back to Directory
            </a>
        </div>

        <!-- Success/Error Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Requests Table -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            @if($requests->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Pending Requests</p>
                    <p class="text-xs text-slate-500 mt-1">There are no dealer registration requests awaiting approval at this time.</p>
                </div>
            @else
                <x-tables.main :headers="['Dealer applicant', 'Submission Date', 'Role Requested', 'Actions']">
                    @foreach($requests as $req)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Identity -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-luxury-charcoal text-white font-bold text-xs uppercase select-none">
                                        {{ substr($req->user->name, 0, 1) }}
                                    </span>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 select-all">{{ $req->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">@ {{ $req->user->username }} &bull; {{ $req->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Submission Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-500 select-all">
                                {{ $req->created_at ? $req->created_at->diffForHumans() : 'N/A' }}
                            </td>

                            <!-- Role Requested -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold uppercase tracking-wider text-luxury-gold select-all">
                                Showroom Dealer
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <!-- Approve Form -->
                                    <form action="{{ route('admin.dealers.approve', $req->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3.5 py-2 text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-xl transition-colors">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('admin.dealers.reject', $req->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject this dealer request and reset their role to customer?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3.5 py-2 text-xs font-bold uppercase tracking-wider bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white rounded-xl transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </x-tables.main>
            @endif
        </div>

    </div>
</x-layout.dashboard>
