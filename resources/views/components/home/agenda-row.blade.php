@props([
    'startDate',
    'endDate' => null,
    'time' => null,
    'location' => null,
    'category' => null,
    'title',
    'status' => null,
    'url' => null,
])

@php
    $startDateValue = $startDate ? \Illuminate\Support\Carbon::parse($startDate) : null;
    $endDateValue = $endDate ? \Illuminate\Support\Carbon::parse($endDate) : null;
    $hasUrl = filled($url) && ! in_array($url, ['#', 'javascript:void(0)'], true);
@endphp

<article>
    @if ($hasUrl)
        <a
            href="{{ $url }}"
            class="grid gap-4 p-4 transition-colors duration-fast hover:bg-primary-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-surface md:grid-cols-[8rem_minmax(0,1fr)]"
        >
    @else
        <div class="grid gap-4 p-4 md:grid-cols-[8rem_minmax(0,1fr)]">
    @endif
            <div class="text-sm text-text-muted">
                @if ($startDateValue)
                    <time datetime="{{ $startDateValue->toDateString() }}" class="block font-bold text-text-strong">
                        {{ $startDateValue->translatedFormat('d M Y') }}
                    </time>
                @else
                    <span class="block font-bold text-text-strong">
                        Tanggal menyusul
                    </span>
                @endif

                @if ($endDateValue)
                    <time datetime="{{ $endDateValue->toDateString() }}" class="block mt-1">
                        sampai {{ $endDateValue->translatedFormat('d M Y') }}
                    </time>
                @endif
            </div>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-3 text-sm text-text-muted">
                    @if (filled($category))
                        <span>{{ $category }}</span>
                    @endif
                    @if (filled($status))
                        <span>Status: {{ $status }}</span>
                    @endif
                </div>

                <h3 class="mt-1 text-lg font-semibold text-text-strong">
                    {{ $title }}
                </h3>

                @if (filled($time) || filled($location))
                    <p class="mt-2 flex flex-col gap-1 text-sm text-text-muted sm:flex-row sm:flex-wrap sm:gap-4">
                        @if (filled($time))
                            <span class="inline-flex items-center gap-2">
                                <x-ui.icon name="clock" class="size-4" />
                                <span>{{ $time }}</span>
                            </span>
                        @endif
                        @if (filled($location))
                            <span class="inline-flex items-center gap-2">
                                <x-ui.icon name="map-pin" class="size-4" />
                                <span>{{ $location }}</span>
                            </span>
                        @endif
                    </p>
                @endif
            </div>
    @if ($hasUrl)
        </a>
    @else
        </div>
    @endif
</article>
