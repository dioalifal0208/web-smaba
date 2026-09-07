@extends('layouts.public')
@section('title', 'Pengumuman')
@section('description', 'Daftar pengumuman resmi SMA Negeri 1 Babat.')
@section('content')
    <section class="bg-surface py-12 md:py-16" aria-labelledby="announcements-heading"><x-ui.container><div class="max-w-container-content">
        <h1 id="announcements-heading" class="text-3xl font-bold text-text-strong md:text-4xl">Pengumuman</h1>
        @if ($announcements->isNotEmpty())
            <div class="mt-8 divide-y divide-border border-y border-border">@foreach ($announcements as $announcement)
                <article class="{{ $announcement->is_important ? 'border-l-2 border-accent bg-accent-soft px-4' : '' }} py-6"><time datetime="{{ $announcement->published_at->copy()->timezone('Asia/Jakarta')->toDateString() }}" class="text-sm text-text-muted">{{ $announcement->published_at->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</time>
                    @if ($announcement->is_important)<span class="ml-3 text-sm font-semibold text-warning">Pengumuman penting</span>@endif
                    <h2 class="mt-2 text-xl font-bold text-text-strong"><a href="{{ route('announcements.show', $announcement->slug) }}" class="rounded-sm hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring">{{ $announcement->title }}</a></h2>
                    @if (filled($announcement->excerpt))<p class="mt-3 text-base leading-relaxed text-text">{{ $announcement->excerpt }}</p>@endif
                </article>@endforeach</div><div class="mt-8">{{ $announcements->links() }}</div>
        @else
            <x-ui.empty-state title="Belum ada pengumuman." message="Pengumuman resmi akan tampil setelah dipublikasikan." class="mt-8" />
        @endif
    </div></x-ui.container></section>
@endsection
