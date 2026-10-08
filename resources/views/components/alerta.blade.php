@props(['tipo' => 'sucesso'])

@php
    $classes = match ($tipo) {
        'erro' => 'bg-red-100 border-red-400 text-red-800',
        'aviso' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
        default => 'bg-green-100 border-green-400 text-green-800',
    };
@endphp

<div {{ $attributes->merge(['class' => 'border-l-4 p-4 rounded '.$classes, 'role' => 'alert']) }}>
    {{ $slot }}
</div>