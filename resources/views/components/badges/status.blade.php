@props(['status'])

@php
    $status = strtolower($status);
    
    $classes = match ($status) {
        // Car Listing Statuses
        'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
        'sold' => 'bg-indigo-50 text-indigo-700 border-indigo-200/80',
        'trash' => 'bg-rose-50 text-rose-700 border-rose-200/80',
        
        // Inquiry Ticket Statuses
        'open' => 'bg-blue-50 text-blue-700 border-blue-200/80',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/80',
        'answered' => 'bg-teal-50 text-teal-700 border-teal-200/80',
        'closed' => 'bg-slate-100 text-slate-500 border-slate-200',
        
        default => 'bg-slate-50 text-slate-600 border-slate-200',
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold tracking-wider uppercase border {{ $classes }}">
    <!-- Small status dot indicator -->
    <span class="h-1 w-1 rounded-full currentColor"></span>
    {{ $status }}
</span>
