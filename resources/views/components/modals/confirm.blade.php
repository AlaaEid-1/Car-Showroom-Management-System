@props([
    'id',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'type' => 'danger'
])

<div x-data="{ open: false }" 
     x-show="open" 
     x-on:open-modal-{{ $id }}.window="open = true" 
     x-on:close-modal-{{ $id }}.window="open = false" 
     x-on:keydown.escape.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
     
    <!-- Backdrop overlay -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
         @click="open = false"></div>

    <!-- Modal Box Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all border border-slate-100">
             
            <!-- Title -->
            <h3 class="text-base font-bold leading-6 text-slate-900">
                {{ $title }}
            </h3>
            
            <!-- Message -->
            <div class="mt-2">
                <p class="text-sm text-slate-500 leading-relaxed">
                    {{ $message }}
                </p>
            </div>

            <!-- Form/Slots custom actions -->
            @if ($slot->isNotEmpty())
                <div class="mt-4">
                    {{ $slot }}
                </div>
            @endif

            <!-- Bottom Action Buttons -->
            <div class="mt-6 flex flex-row-reverse gap-3">
                <button type="button" 
                        class="inline-flex justify-center rounded-xl px-4 py-2.5 text-sm font-semibold tracking-wide uppercase transition-colors {{ $type === 'danger' ? 'bg-rose-600 text-white hover:bg-rose-700' : 'bg-luxury-charcoal text-white hover:bg-luxury-gold' }}"
                        @click="open = false; $dispatch('confirm-modal-{{ $id }}')">
                    {{ $confirmText }}
                </button>
                <button type="button" 
                        class="inline-flex justify-center rounded-xl px-4 py-2.5 text-sm font-semibold tracking-wide uppercase border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                        @click="open = false">
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
