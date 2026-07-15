@props(['car'])

<div class="group bg-white rounded-2xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 flex flex-col h-full relative">
    
    <!-- Image Area with Glassmorphic Badges -->
    <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
        @if($car->images->isNotEmpty())
            <img src="{{ asset('storage/' . $car->images->first()->path) }}" 
                 alt="{{ $car->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
        @else
            <!-- Placeholder premium empty state -->
            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>
                <span class="text-xs uppercase tracking-wider font-semibold">No Image Available</span>
            </div>
        @endif

        <!-- Floating Badges -->
        <div class="absolute top-4 left-4 flex gap-2">
            <x-badges.status :status="$car->status" />
            <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-semibold tracking-wider px-2.5 py-1 rounded-full uppercase">
                {{ $car->year }}
            </span>
        </div>
    </div>

    <!-- Details Box -->
    <div class="p-6 flex flex-col flex-1">
        <span class="text-xs font-semibold tracking-widest text-slate-400 uppercase mb-1">
            {{ $car->brand }}
        </span>
        <h3 class="text-lg font-bold text-slate-900 group-hover:text-luxury-gold transition-colors truncate">
            {{ $car->title }}
        </h3>
        
        <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed flex-grow">
            {{ $car->description ?? 'No specifications provided.' }}
        </p>

        <hr class="border-slate-100 my-4">

        <!-- Footer specs & price -->
        <div class="flex items-center justify-between mt-auto">
            <div class="text-xs font-semibold tracking-wider text-slate-400 uppercase">
                Est. Value
            </div>
            <div class="text-xl font-extrabold text-slate-900">
                ${{ number_format($car->price) }}
            </div>
        </div>

        <a href="{{ route('cars.show', $car->id) }}" 
           class="absolute inset-0 z-10" 
           title="View Details for {{ $car->title }}">
            <span class="sr-only">View vehicle details</span>
        </a>
    </div>
</div>
