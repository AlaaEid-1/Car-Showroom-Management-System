@props(['name', 'label' => null, 'value' => '', 'required' => false, 'rows' => 4])

<div class="w-full">
    @if ($label)
        <x-forms.label :value="$label" :required="$required" :for="$name" />
    @endif
    
    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border px-4.5 py-3 text-sm font-medium text-slate-800 bg-white placeholder-slate-400 focus:outline-none transition-all duration-200 resize-y' . 
            ($errors->has($name) 
                ? ' border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/10' 
                : ' border-slate-250 focus:border-luxury-gold focus:ring-2 focus:ring-luxury-gold/10')
        ]) }}
    >{{ old($name, $value) }}</textarea>
    
    <x-forms.error :field="$name" />
</div>
