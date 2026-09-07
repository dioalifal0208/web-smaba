@props([
    'lead',
    'stories' => [],
])

@php
    $stories = collect($stories);
    $leadDate = data_get($lead, 'published_at', data_get($lead, 'date'));
    $leadDateValue = $leadDate ? \Illuminate\Support\Carbon::parse($leadDate)->timezone('Asia/Jakarta') : null;
    $leadUrl = data_get($lead, 'url');
    $hasLeadUrl = filled($leadUrl) && ! in_array($leadUrl, ['#', 'javascript:void(0)'], true);
@endphp

<div {{ $attributes->class('grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(18rem,0.9fr)]') }}>
    <article class="border border-border bg-surface rounded-lg overflow-hidden">
        <x-ui.placeholder-image
            ratio="video"
            label="Foto berita belum tersedia."
            class="rounded-none border-0 border-b border-border"
        />

        <div class="p-5 md:p-6">
            <div class="flex flex-wrap items-center gap-3 text-sm text-text-muted">
                @if (filled(data_get($lead, 'category')))
                    <span class="font-medium text-secondary">
                        {{ data_get($lead, 'category') }}
                    </span>
                @endif

                @if ($leadDateValue)
                    <time datetime="{{ $leadDateValue->toDateString() }}">
                        {{ $leadDateValue->translatedFormat('d F Y') }}
                    </time>
                @endif
            </div>

            <h3 class="mt-3 text-xl font-bold text-text-strong">
                @if ($hasLeadUrl)
                    <a
                        href="{{ $leadUrl }}"
                        class="rounded-sm transition-colors duration-fast hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-surface"
                    >
                        {{ data_get($lead, 'title') }}
                    </a>
                @else
                    {{ data_get($lead, 'title') }}
                @endif
            </h3>

            @if (filled(data_get($lead, 'excerpt', data_get($lead, 'summary'))))
                <p class="mt-3 text-base text-text">
                    {{ data_get($lead, 'excerpt', data_get($lead, 'summary')) }}
                </p>
            @endif
        </div>
    </article>

    @if ($stories->isNotEmpty())
        <div class="divide-y divide-border border-y border-border">
            @foreach ($stories as $story)
                @php
                    $storyDate = data_get($story, 'published_at', data_get($story, 'date'));
                    $storyDateValue = $storyDate ? \Illuminate\Support\Carbon::parse($storyDate)->timezone('Asia/Jakarta') : null;
                    $storyUrl = data_get($story, 'url');
                    $hasStoryUrl = filled($storyUrl) && ! in_array($storyUrl, ['#', 'javascript:void(0)'], true);
                @endphp

                <article class="py-4">
                    <div class="flex flex-wrap items-center gap-3 text-sm text-text-muted">
                        @if (filled(data_get($story, 'category')))
                            <span class="font-medium text-secondary">
                                {{ data_get($story, 'category') }}
                            </span>
                        @endif

                        @if ($storyDateValue)
                            <time datetime="{{ $storyDateValue->toDateString() }}">
                                {{ $storyDateValue->translatedFormat('d F Y') }}
                            </time>
                        @endif
                    </div>

                    <h3 class="mt-2 text-base font-semibold text-text-strong">
                        @if ($hasStoryUrl)
                            <a
                                href="{{ $storyUrl }}"
                                class="rounded-sm transition-colors duration-fast hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-surface"
                            >
                                {{ data_get($story, 'title') }}
                            </a>
                        @else
                            {{ data_get($story, 'title') }}
                        @endif
                    </h3>

                    @if (filled(data_get($story, 'excerpt', data_get($story, 'summary'))))
                        <p class="mt-2 text-sm text-text-muted">
                            {{ data_get($story, 'excerpt', data_get($story, 'summary')) }}
                        </p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div>
