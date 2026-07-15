@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold tracking-wider text-slate-500 uppercase mb-1.5']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-rose-500 font-bold">*</span>
    @endif
</label>
