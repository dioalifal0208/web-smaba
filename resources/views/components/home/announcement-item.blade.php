@props([
    'title',
    'date' => null,
    'excerpt' => null,
    'url' => null,
    'isImportant' => true,
])

@php
    $dateValue = $date ? \Illuminate\Support\Carbon::parse($date) : null;
    $hasUrl = filled($url) && ! in_array($url, ['#', 'javascript:void(0)'], true);
@endphp

<article class="{{ $isImportant ? 'border-l-2 border-accent bg-accent-soft px-4' : 'px-4' }}">
    @if ($hasUrl)
        <a
            href="{{ $url }}"
            class="block py-5 transition-colors duration-fast hover:bg-surface-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-surface"
        >
    @else
        <div class="py-5">
    @endif
            <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                <h3 class="text-lg font-semibold text-text-strong">
                    {{ $title }}
                </h3>

                @if ($dateValue)
                    <time datetime="{{ $dateValue->toDateString() }}" class="text-sm font-medium text-text-muted">
                        {{ $dateValue->translatedFormat('d F Y') }}
                    </time>
                @endif
            </div>

            @if (filled($excerpt))
                <p class="mt-2 max-w-container-content text-base text-text">
                    {{ $excerpt }}
                </p>
            @endif

            @if ($isImportant)
                <p class="mt-3 text-sm font-semibold text-warning">
                    Pengumuman penting
                </p>
            @endif
    @if ($hasUrl)
        </a>
    @else
        </div>
    @endif
</article>
