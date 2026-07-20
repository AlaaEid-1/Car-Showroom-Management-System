@php
    $isDealerView = request()->routeIs('dashboarddealer.cars.show') && Auth::check() && Auth::user()->role === 'dealer' && Auth::id() === $car->user_id;
@endphp

@if ($isDealerView)
    <!-- DEALER PREVIEW VIEW -->
    <x-layout.dashboard title="Preview: {{ $car->title }} | Alaa Motors" header="Listing Preview">
        <div class="space-y-8">
            <!-- Header bar with actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 select-all">{{ $car->title }}</h2>
                    <p class="text-xs text-slate-400 font-medium mt-1 uppercase tracking-wider">
                        Brand: {{ $car->brand }} | Model: {{ $car->model }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboarddealer.cars.edit', $car->id) }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        Edit Vehicle
                    </a>
                    
                    <form action="{{ route('dashboarddealer.cars.destroy', $car->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Move this vehicle to trash?')" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider bg-rose-600 text-white hover:bg-rose-700 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Vehicle Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Specs Card -->
                <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4 md:col-span-2">
                    <h3 class="text-xs font-bold tracking-widest text-slate-400 uppercase">Technical Overview</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
                        <div class="border-l-2 border-slate-100 pl-4 py-1">
                            <span class="text-xs text-slate-400 uppercase tracking-wider block font-medium">Year</span>
                            <span class="font-bold text-slate-900 block mt-1 select-all">{{ $car->year }}</span>
                        </div>
                        <div class="border-l-2 border-slate-100 pl-4 py-1">
                            <span class="text-xs text-slate-400 uppercase tracking-wider block font-medium">Value</span>
                            <span class="font-bold text-slate-900 block mt-1 select-all">${{ number_format($car->price) }}</span>
                        </div>
                        <div class="border-l-2 border-slate-100 pl-4 py-1">
                            <span class="text-xs text-slate-400 uppercase tracking-wider block font-medium">Status</span>
                            <span class="block mt-1">
                                <x-badges.status :status="$car->status" />
                            </span>
                        </div>
                        <div class="border-l-2 border-slate-100 pl-4 py-1">
                            <span class="text-xs text-slate-400 uppercase tracking-wider block font-medium">Showroom</span>
                            <span class="font-bold text-slate-900 block mt-1 truncate" title="{{ $car->showroom->name ?? 'None' }}">
                                {{ $car->showroom->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold tracking-widest text-slate-400 uppercase mb-2">Description</h4>
                        <p class="text-sm text-slate-600 leading-relaxed select-text">{{ $car->description ?? 'No description added yet.' }}</p>
                    </div>
                </div>

                <!-- Main Photo -->
                <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm flex flex-col items-center justify-center">
                    <h3 class="text-xs font-bold tracking-widest text-slate-400 uppercase mb-4 self-start">Cover Photo</h3>
                    <div class="aspect-[4/3] w-full rounded-xl overflow-hidden bg-slate-50 border border-slate-100">
                        @if($car->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $car->images->first()->path) }}" alt="{{ $car->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <span class="text-xs font-bold tracking-wider uppercase text-slate-400">No Image Uploaded</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-layout.dashboard>
@else
    <!-- PUBLIC BUYER VIEW -->
    <x-layout.app title="{{ $car->title }} | Alaa Motors" description="{{ Str::limit($car->description, 150) }}">
        
        <div class="bg-slate-100 border-b border-slate-200/50 py-8 mb-12">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs font-bold tracking-widest text-luxury-gold uppercase block mb-1">
                        {{ $car->brand }} {{ $car->model }}
                    </span>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight select-all">
                        {{ $car->title }}
                    </h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-3xl font-extrabold text-slate-900 select-all">
                        ${{ number_format($car->price) }}
                    </div>
                    @auth
                        @if (Auth::user()->role === 'customer')
                            @php
                                $isFavorited = Auth::user()->favoriteCars()->where('car_id', $car->id)->exists();
                            @endphp
                            <form action="{{ $isFavorited ? route('cars.favorite.destroy', $car->id) : route('cars.favorite.store', $car->id) }}" method="POST" class="inline">
                                @csrf
                                @if($isFavorited)
                                    @method('DELETE')
                                @endif
                                <button type="submit" class="inline-flex items-center justify-center p-2.5 rounded-xl border {{ $isFavorited ? 'border-red-200 bg-red-50 text-red-500' : 'border-slate-200 bg-white text-slate-400 hover:text-red-500' }} transition-colors" title="{{ $isFavorited ? 'Remove from favorites' : 'Add to favorites' }}">
                                    <svg class="h-6 w-6" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @else
                        {{-- Guest: show favorite button that redirects to login --}}
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center p-2.5 rounded-xl border border-slate-200 bg-white text-slate-400 hover:text-red-500 transition-colors" title="Sign in to save to favorites">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-4 grid grid-cols-1 lg:grid-cols-3 gap-12 mb-24">
            
            <!-- Left Side: Photo Slider & Specs -->
            <div class="lg:col-span-2 space-y-10">
                
                <!-- Alpine.js Photo Slider Component -->
                <div x-data="{ 
                    activeSlide: 0, 
                    slides: [
                        @foreach($car->images as $img)
                            '{{ asset('storage/' . $img->path) }}',
                        @endforeach
                    ]
                }" class="bg-white border border-slate-200/60 rounded-3xl overflow-hidden p-3 shadow-sm">
                    
                    <!-- Main Frame -->
                    <div class="aspect-[16/10] w-full rounded-2xl overflow-hidden bg-slate-50 relative border border-slate-100">
                        <template x-if="slides.length > 0">
                            <img :src="slides[activeSlide]" alt="Vehicle photo" class="w-full h-full object-cover transition-all duration-300">
                        </template>
                        <template x-if="slides.length === 0">
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                <svg class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                </svg>
                                <span class="text-xs uppercase tracking-wider font-semibold">No Image Uploaded</span>
                            </div>
                        </template>

                        <!-- Floating Badges -->
                        <div class="absolute top-4 left-4">
                            <x-badges.status :status="$car->status" />
                        </div>

                        <!-- Prev/Next Overlay buttons -->
                        <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 hover:opacity-100 transition-opacity duration-300" x-show="slides.length > 1">
                            <button type="button" 
                                    @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1"
                                    class="h-10 w-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                            <button type="button" 
                                    @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1"
                                    class="h-10 w-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Thumbnails Tray -->
                    <div class="flex gap-3 mt-4 overflow-x-auto pb-1" x-show="slides.length > 1">
                        <template x-for="(slide, idx) in slides" :key="idx">
                            <button type="button" 
                                    @click="activeSlide = idx"
                                    class="w-20 aspect-[16/10] rounded-lg overflow-hidden border-2 transition-colors shrink-0 bg-slate-50"
                                    :class="activeSlide === idx ? 'border-luxury-gold' : 'border-transparent'">
                                <img :src="slide" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Description & Specs grid -->
                <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900">Vehicle Specifications</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
                        <div class="border-l-2 border-slate-100 pl-4">
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Brand</span>
                            <span class="font-bold text-slate-800 block mt-1 select-all">{{ $car->brand }}</span>
                        </div>
                        <div class="border-l-2 border-slate-100 pl-4">
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Model</span>
                            <span class="font-bold text-slate-800 block mt-1 select-all">{{ $car->model }}</span>
                        </div>
                        <div class="border-l-2 border-slate-100 pl-4">
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Year</span>
                            <span class="font-bold text-slate-800 block mt-1 select-all">{{ $car->year }}</span>
                        </div>
                        <div class="border-l-2 border-slate-100 pl-4">
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Showroom Location</span>
                            <span class="font-bold text-slate-800 block mt-1 select-all">{{ $car->showroom->location ?? 'Palestine' }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <h4 class="text-xs font-bold tracking-widest text-slate-400 uppercase mb-3">Dealer Description</h4>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line select-text">{{ $car->description ?? 'No specifications description added.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Showroom profile & Inquiry box -->
            <div class="space-y-8">
                
                <!-- Showroom card -->
                <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
                    <span class="text-[10px] font-bold tracking-widest text-luxury-gold uppercase block">Listing Dealership</span>
                    <h3 class="text-lg font-bold text-slate-900 select-all">{{ $car->showroom->name ?? 'Alaa Motors Dealership' }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ $car->showroom->description ?? 'Premium luxury automotive showroom representing the finest vehicles.' }}</p>
                    
                    <hr class="border-slate-100">
                    
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center gap-2 text-slate-650">
                            <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span class="font-medium select-all">{{ $car->showroom->location ?? 'Gaza, Palestine' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-650">
                            <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.806-5.194-4.177-7-7l1.3-1.3c.372-.372.502-.914.364-1.423L6.059 4.19a1.25 1.25 0 0 0-1.226-.94H3.375A2.25 2.25 0 0 0 1.125 5.5v1.25Z" />
                            </svg>
                            <span class="font-medium select-all">{{ $car->showroom->phone ?? 'Contact Showroom' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Box -->
                <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold tracking-wider text-slate-900 uppercase">Send Dealer Inquiry</h3>
                    
                    @auth
                        @if (session('success'))
                            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-semibold">
                                {{ session('success') }}
                            </div>
                        @elseif (session('error'))
                            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-semibold">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($car->user_id === Auth::id())
                            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-amber-800 text-xs font-semibold leading-relaxed">
                                You own this listing. Inquiries are disabled.
                            </div>
                        @else
                            <form action="{{ route('inquiries.store', $car->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <x-forms.textarea name="message" 
                                                  placeholder="Hello, I am interested in this vehicle and would like to receive details regarding availability..." 
                                                  required 
                                                  rows="4" />
                                
                                <button type="submit" class="w-full flex items-center justify-center gap-2 h-11 rounded-xl text-xs font-semibold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-300 shadow-md">
                                    Send Message
                                </button>
                            </form>
                        @endif
                    @else
                        <!-- Guest Prompt -->
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 text-center space-y-4">
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                Sign in to your portal to submit secure inquiries directly to this showroom.
                            </p>
                            <a href="{{ route('login') }}" class="w-full flex items-center justify-center h-10 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-charcoal text-white hover:bg-luxury-gold transition-colors duration-200 shadow-sm">
                                Portal Sign In
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Test Drive Request Box -->
                @auth
                    @if (Auth::user()->role === 'customer' && $car->user_id !== Auth::id())
                        <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
                            <h3 class="text-sm font-bold tracking-wider text-slate-900 uppercase">Request Test Drive</h3>

                            @if(session('error'))
                                <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-semibold">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <form action="{{ route('cars.test-drive.store', $car->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Preferred Date & Time</label>
                                    <input type="datetime-local" 
                                           name="scheduled_at" 
                                           required 
                                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                           class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs outline-none focus:border-luxury-gold">
                                    <x-forms.error field="scheduled_at" />
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Additional Notes (Optional)</label>
                                    <textarea name="notes" 
                                              placeholder="e.g. Preferred time slots, contact preferences..." 
                                              rows="2"
                                              class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs outline-none focus:border-luxury-gold"></textarea>
                                    <x-forms.error field="notes" />
                                </div>

                                <button type="submit" class="w-full flex items-center justify-center gap-2 h-11 rounded-xl text-xs font-semibold uppercase tracking-wider bg-luxury-gold text-white hover:bg-luxury-gold-hover transition-colors duration-300 shadow-md">
                                    Book Test Drive
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    {{-- Guest: show sign-in prompt for test drive --}}
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold tracking-wider text-slate-900 uppercase">Request Test Drive</h3>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 text-center space-y-4">
                            <svg class="h-8 w-8 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                Sign in to schedule a test drive for this vehicle.
                            </p>
                            <a href="{{ route('login') }}" class="w-full flex items-center justify-center h-10 rounded-xl text-xs font-bold uppercase tracking-wider bg-luxury-gold text-white hover:bg-luxury-charcoal transition-colors duration-200 shadow-sm">
                                Sign In to Book
                            </a>
                        </div>
                    </div>
                @endauth

            </div>

        </div>
    </x-layout.app>
@endif
