<x-layout.dashboard title="Customer Workspace | Alaa Motors" header="Customer Workspace">
    
    <div x-data="{ 
            activeTab: ['#profile', '#favorites', '#testdrives'].includes(window.location.hash) 
                ? window.location.hash.substring(1) 
                : 'inquiries' 
         }"
         @hashchange.window="activeTab = ['#profile', '#favorites', '#testdrives'].includes(window.location.hash) 
             ? window.location.hash.substring(1) 
             : 'inquiries'"
         class="space-y-8">

        <!-- Custom styled tabs -->
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'inquiries'; window.location.hash = '#inquiries'"
                    :class="activeTab === 'inquiries' ? 'border-luxury-gold text-luxury-gold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="py-4 px-6 border-b-2 font-bold text-sm tracking-wider uppercase focus:outline-none transition-colors duration-250">
                My Inquiries
            </button>
            <button @click="activeTab = 'favorites'; window.location.hash = '#favorites'"
                    :class="activeTab === 'favorites' ? 'border-luxury-gold text-luxury-gold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="py-4 px-6 border-b-2 font-bold text-sm tracking-wider uppercase focus:outline-none transition-colors duration-250">
                My Favorites
            </button>
            <button @click="activeTab = 'testdrives'; window.location.hash = '#testdrives'"
                    :class="activeTab === 'testdrives' ? 'border-luxury-gold text-luxury-gold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="py-4 px-6 border-b-2 font-bold text-sm tracking-wider uppercase focus:outline-none transition-colors duration-250">
                Test Drives
            </button>
            <button @click="activeTab = 'profile'; window.location.hash = '#profile'"
                    :class="activeTab === 'profile' ? 'border-luxury-gold text-luxury-gold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="py-4 px-6 border-b-2 font-bold text-sm tracking-wider uppercase focus:outline-none transition-colors duration-250">
                Profile Settings
            </button>
        </div>

        <!-- Session Status Notifications -->
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Inquiries Tab Panel -->
        <div x-show="activeTab === 'inquiries'" class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Sent Inquiries</h2>
                <p class="text-xs text-slate-500 mt-1">Track status and conversation details of your active vehicle inquiries.</p>
            </div>

            @if ($inquiries->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Sent Inquiries</p>
                    <p class="text-xs text-slate-500 mt-1">You haven't initiated any inquiries yet. View the fleet to ask about a vehicle.</p>
                    <div class="pt-4">
                        <a href="{{ route('cars.search') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200">
                            Browse Cars
                        </a>
                    </div>
                </div>
            @else
                <x-tables.main :headers="['Car Detail', 'Recipient (Dealer / Showroom)', 'Subject', 'Last Response', 'Status', 'Actions']">
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

                            <!-- Dealer / Showroom -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-800 select-all">{{ $inquiry->dealer->name ?? 'Dealer' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 select-all">{{ $inquiry->car->showroom->name ?? 'N/A Showroom' }}</div>
                            </td>

                            <!-- Subject -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider select-all">
                                    {{ $inquiry->subject ?? 'General Inquiry' }}
                                </span>
                            </td>

                            <!-- Last response -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-medium text-slate-650 select-all">
                                    {{ $inquiry->last_message_at ? $inquiry->last_message_at->diffForHumans() : 'N/A' }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badges.status :status="$inquiry->status" />
                            </td>

                            <!-- View Actions -->
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

        <!-- Favorites Tab Panel -->
        <div x-show="activeTab === 'favorites'" class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">My Favorites</h2>
                <p class="text-xs text-slate-500 mt-1">Bookmark vehicle listings you are tracking or interested in.</p>
            </div>

            @if ($favorites->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">Wishlist is Empty</p>
                    <p class="text-xs text-slate-500 mt-1">Browse our collection of luxury cars to start saving your favorites.</p>
                    <div class="pt-4">
                        <a href="{{ route('cars.search') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200">
                            Explore Vehicles
                        </a>
                    </div>
                </div>
            @else
                <x-tables.main :headers="['Car Detail', 'Year / Model', 'Price', 'Actions']">
                    @foreach ($favorites as $fav)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        @if ($fav->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $fav->images->first()->path) }}" alt="{{ $fav->title }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 select-all">{{ $fav->title }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 select-all">{{ $fav->brand }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-700 select-all">{{ $fav->model }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 select-all">Year: {{ $fav->year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-extrabold text-slate-900 select-all">${{ number_format($fav->price) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold uppercase tracking-wider space-x-2">
                                <a href="{{ route('cars.show', $fav->id) }}" class="text-luxury-gold hover:text-slate-800 transition-colors">
                                    View details
                                </a>
                                <form action="{{ route('cars.favorite.destroy', $fav->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </x-tables.main>
            @endif
        </div>

        <!-- Test Drives Tab Panel -->
        <div x-show="activeTab === 'testdrives'" class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">My Test Drives</h2>
                <p class="text-xs text-slate-500 mt-1">Monitor the status and details of your booked test drive requests.</p>
            </div>

            @if ($testDrives->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">No Bookings Found</p>
                    <p class="text-xs text-slate-500 mt-1">Schedule a test drive on any published vehicle details page.</p>
                </div>
            @else
                <x-tables.main :headers="['Car Reference', 'Preferred Schedule', 'Showroom / Dealer', 'Notes', 'Status']">
                    @foreach ($testDrives as $td)
                        <tr class="hover:bg-slate-50/50 transition-colors">
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
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-700 font-semibold select-all">
                                {{ \Carbon\Carbon::parse($td->scheduled_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-slate-700 font-bold select-all">{{ $td->car->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-[200px] truncate text-xs text-slate-500 font-medium select-text" title="{{ $td->notes }}">
                                {{ $td->notes ?? '-' }}
                            </td>
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
                        </tr>
                    @endforeach
                </x-tables.main>
            @endif
        </div>

        <!-- Profile Tab Panel -->
        <div x-show="activeTab === 'profile'" class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm max-w-2xl space-y-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Profile Information</h2>
                <p class="text-xs text-slate-500 mt-1">Configure and manage your customer account identifiers.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <!-- Name Input -->
                <x-forms.input name="name" 
                               label="Full Name" 
                               required 
                               value="{{ Auth::user()->name }}" />

                <!-- Username Input -->
                <x-forms.input name="username" 
                               label="Username" 
                               required 
                               value="{{ Auth::user()->username }}" />

                <!-- Email Input -->
                <x-forms.input name="email" 
                               label="Email Address" 
                               type="email" 
                               required 
                               value="{{ Auth::user()->email }}" />

                <!-- Timezone Selector -->
                <x-forms.select name="timezone" label="Preferred Timezone" required>
                    <option value="UTC" {{ Auth::user()->timezone === 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                    <option value="Asia/Gaza" {{ Auth::user()->timezone === 'Asia/Gaza' ? 'selected' : '' }}>Asia/Gaza</option>
                    <option value="Asia/Jerusalem" {{ Auth::user()->timezone === 'Asia/Jerusalem' ? 'selected' : '' }}>Asia/Jerusalem</option>
                    <option value="America/New_York" {{ Auth::user()->timezone === 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                    <option value="Europe/London" {{ Auth::user()->timezone === 'Europe/London' ? 'selected' : '' }}>London / Greenwich Time</option>
                </x-forms.select>

                <!-- Country Code Input -->
                <x-forms.input name="country_code" 
                               label="Country Code (2 Letters)" 
                               placeholder="e.g. PS" 
                               maxlength="2" 
                               value="{{ Auth::user()->country_code }}" />

                <!-- Submit Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-250 shadow-md">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layout.dashboard>
