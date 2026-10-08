@props(['variante' => 'primario'])

@php
    $classes = match ($variante) {
        'secundario' => 'bg-gray-200 text-gray-800 hover:bg-gray-300',
        'perigo' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 rounded font-semibold transition '.$classes]) }}>
    {{ $slot }}
</button>