@props([
    'title',
    'message',
])

<div {{ $attributes->class('border-l-2 border-accent py-2 pl-4 text-text') }}>
    <p class="font-semibold text-text-strong">
        {{ $title }}
    </p>
    <p class="mt-2 max-w-container-content text-base text-text-muted">
        {{ $message }}
    </p>
</div>
