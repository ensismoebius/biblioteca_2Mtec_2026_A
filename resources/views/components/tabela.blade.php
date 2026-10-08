<div class="overflow-x-auto rounded border border-gray-200">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 bg-white text-sm text-gray-900 [&_th]:px-4 [&_th]:py-2 [&_th]:text-left [&_th]:font-semibold [&_td]:px-4 [&_td]:py-2 [&_tbody_tr:nth-child(even)]:bg-gray-50 [&_tbody_tr:hover]:bg-gray-100']) }}>
        {{ $slot }}
    </table>
</div>