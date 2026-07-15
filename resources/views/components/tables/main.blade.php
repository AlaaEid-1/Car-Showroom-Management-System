@props(['headers' => []])

<div class="overflow-x-auto border border-slate-200/60 rounded-xl bg-white shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold tracking-wider text-slate-500 uppercase select-none">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="px-6 py-4">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
