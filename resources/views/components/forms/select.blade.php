@props(['name', 'label' => null, 'value' => '', 'required' => false])

<div class="w-full">
    @if ($label)
        <x-forms.label :value="$label" :required="$required" :for="$name" />
    @endif
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border px-4.5 py-3 text-sm font-medium text-slate-800 bg-white focus:outline-none transition-all duration-200 appearance-none bg-[url(\'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%20%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%209l3%203%203-3%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E\')] bg-[right_1.125rem_center] bg-[length:1.25rem_1.25rem] bg-no-repeat pr-10' . 
            ($errors->has($name) 
                ? ' border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/10' 
                : ' border-slate-200 focus:border-luxury-gold focus:ring-2 focus:ring-luxury-gold/10')
        ]) }}
    >
        {{ $slot }}
    </select>
    
    <x-forms.error :field="$name" />
</div>
