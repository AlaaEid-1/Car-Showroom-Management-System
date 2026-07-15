@props(['value', 'label', 'description' => null])

<div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm flex flex-col justify-between">
    <div>
        <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase">
            {{ $label }}
        </span>
        <div class="text-3xl font-extrabold text-slate-900 mt-2 select-all">
            {{ $value }}
        </div>
    </div>
    
    @if ($description)
        <div class="text-xs text-slate-500 mt-3 font-medium flex items-center gap-1">
            <span class="text-emerald-500 inline-flex items-center">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                </svg>
            </span>
            {{ $description }}
        </div>
    @endif
</div>
