@props([
    'name',
])

@php
    $paths = [
        'arrow-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0-6-6m6 6-6 6" />',
        'external-link' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14 4h6m0 0v6m0-6-8 8" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 14v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h4" />',
        'clock' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
        'map-pin' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />',
        'service' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 4v4M16 10v4M10 16v4" />',
    ];

    $path = $paths[$name] ?? $paths['arrow-right'];
@endphp

<svg
    {{ $attributes->class('shrink-0') }}
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="1.8"
    aria-hidden="true"
    focusable="false"
>
    {!! $path !!}
</svg>
