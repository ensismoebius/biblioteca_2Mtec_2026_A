@props(['name', 'label' => null, 'ajuda' => null, 'type' => 'text', 'value' => null])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded border-gray-300 shadow-sm']) }}>

    @if ($ajuda)
        <p class="mt-1 text-xs text-gray-500">{{ $ajuda }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>