@props([
    'ratio' => 'video',
    'label' => 'Media belum tersedia.',
])

@php
    $aspectClass = match ($ratio) {
        'landscape' => 'aspect-[4/3]',
        'square' => 'aspect-square',
        default => 'aspect-video',
    };
@endphp

<figure {{ $attributes->class([$aspectClass, 'flex w-full items-center justify-center overflow-hidden rounded-lg border border-border bg-surface-muted']) }}>
    <figcaption class="px-4 text-center text-sm font-medium text-text-muted">
        {{ $label }}
    </figcaption>
</figure>
