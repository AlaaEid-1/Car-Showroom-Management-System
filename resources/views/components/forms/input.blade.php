@props(['name', 'label' => null, 'type' => 'text', 'value' => '', 'required' => false])

<div class="w-full">
    @if ($label)
        <x-forms.label :value="$label" :required="$required" :for="$name" />
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border px-4.5 py-3 text-sm font-medium text-slate-800 bg-white placeholder-slate-450 focus:outline-none transition-all duration-200' . 
            ($errors->has($name) 
                ? ' border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/10' 
                : ' border-slate-250 focus:border-luxury-gold focus:ring-2 focus:ring-luxury-gold/10')
        ]) }}
    >
    
    <x-forms.error :field="$name" />
</div>
