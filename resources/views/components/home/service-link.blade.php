@props([
    'name',
    'description' => null,
    'url' => null,
    'status' => null,
    'accessType' => null,
    'isExternal' => false,
])

@php
    $hasUrl = filled($url) && ! in_array($url, ['#', 'javascript:void(0)'], true);
@endphp

<div class="border border-border bg-surface rounded-lg">
    @if ($hasUrl)
        <a
            href="{{ $url }}"
            @if ($isExternal) target="_blank" rel="noopener noreferrer" aria-label="{{ $name }} membuka tab baru" @endif
            class="group flex min-h-11 gap-4 p-4 transition-colors duration-fast hover:bg-primary-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
        >
    @else
        <div class="flex gap-4 p-4">
    @endif
            <x-ui.icon name="service" class="mt-1 size-5 text-secondary" />

            <div class="min-w-0 flex-1">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <h3 class="text-base font-semibold text-text-strong">
                        {{ $name }}
                    </h3>

                    @if ($hasUrl)
                        <span class="inline-flex items-center gap-1 text-sm font-medium text-primary">
                            Buka layanan
                            @if ($isExternal)
                                <span class="sr-only">, membuka tab baru</span>
                                <x-ui.icon name="external-link" class="size-4" />
                            @else
                                <x-ui.icon name="arrow-right" class="size-4" />
                            @endif
                        </span>
                    @endif
                </div>

                @if (filled($description))
                    <p class="mt-1 text-sm text-text-muted">
                        {{ $description }}
                    </p>
                @endif

                @if (filled($status) || filled($accessType))
                    <p class="mt-3 text-sm text-text">
                        @if (filled($status))
                            <span>Status: {{ $status }}</span>
                        @endif
                        @if (filled($status) && filled($accessType))
                            <span aria-hidden="true"> / </span>
                        @endif
                        @if (filled($accessType))
                            <span>Akses: {{ $accessType }}</span>
                        @endif
                    </p>
                @endif
            </div>
    @if ($hasUrl)
        </a>
    @else
        </div>
    @endif
</div>
