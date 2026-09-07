@props([
    'title',
    'id',
    'actionUrl' => null,
    'actionLabel' => null,
])

@php
    $hasAction = filled($actionUrl)
        && filled($actionLabel)
        && ! in_array($actionUrl, ['#', 'javascript:void(0)'], true);
@endphp

<div {{ $attributes->class('flex flex-col gap-4 border-b border-border pb-4 sm:flex-row sm:items-end sm:justify-between') }}>
    <h2 id="{{ $id }}" class="text-2xl font-bold text-text-strong">
        {{ $title }}
    </h2>

    @if ($hasAction)
        <a
            href="{{ $actionUrl }}"
            class="inline-flex min-h-11 items-center gap-2 self-start rounded-md border border-border px-4 py-2 text-sm font-semibold text-primary transition-colors duration-fast hover:border-primary hover:bg-primary-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-surface"
        >
            <span>{{ $actionLabel }}</span>
            <x-ui.icon name="arrow-right" class="size-4" />
        </a>
    @endif
</div>
