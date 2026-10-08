@props(['status' => 'disponivel'])

@php
    $classes = match ($status) {
        'emprestado' => 'bg-yellow-100 text-yellow-800',
        'atrasado' => 'bg-red-100 text-red-800',
        default => 'bg-green-100 text-green-800',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$classes]) }}>
    {{ $slot }}
</span>