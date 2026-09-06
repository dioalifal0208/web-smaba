{{--
    Container — anonymous Blade component
    Sumber token: MASTER.md §6

    Penggunaan:
        <x-ui.container>…</x-ui.container>
        <x-ui.container size="content">…</x-ui.container>
        <x-ui.container size="narrow" class="py-16">…</x-ui.container>

    Prop size:
        page    → max-w-container-page (1280px) — default, layout utama publik
        content → max-w-container-content (768px) — artikel, long-form
        narrow  → max-w-container-narrow (672px)  — form, teks fokus
        wide    → max-w-container-wide (1440px)   — media khusus

    Padding horizontal mengikuti gutter mobile/tablet/desktop dari MASTER.md §6.
--}}

@props([
    'size' => 'page',
])

@php
    $maxWidth = match ($size) {
        'content' => 'max-w-container-content',
        'narrow'  => 'max-w-container-narrow',
        'wide'    => 'max-w-container-wide',
        default   => 'max-w-container-page',
    };
@endphp

<div
    {{ $attributes->class([
        $maxWidth,
        'mx-auto w-full',
        'px-4 md:px-6 lg:px-8',   {{-- gutter-mobile / gutter-tablet / gutter-desktop --}}
    ]) }}
>
    {{ $slot }}
</div>
