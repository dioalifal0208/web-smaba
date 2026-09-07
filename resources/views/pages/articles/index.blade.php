@extends('layouts.public')

@section('title', 'Berita')
@section('description', 'Daftar berita resmi SMA Negeri 1 Babat.')

@section('content')
    <section class="bg-surface py-12 md:py-16" aria-labelledby="articles-heading"><x-ui.container><div class="max-w-container-content">
        <h1 id="articles-heading" class="text-3xl font-bold text-text-strong md:text-4xl">Berita</h1>
        @if ($articles->isNotEmpty())
            <div class="mt-8 divide-y divide-border border-y border-border">
                @foreach ($articles as $article)
                    <article class="py-6"><time datetime="{{ $article->published_at->copy()->timezone('Asia/Jakarta')->toDateString() }}" class="text-sm font-medium text-text-muted">{{ $article->published_at->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</time>
                        <h2 class="mt-2 text-xl font-bold text-text-strong"><a href="{{ route('articles.show', $article->slug) }}" class="rounded-sm hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring">{{ $article->title }}</a></h2>
                        @if (filled($article->excerpt))<p class="mt-3 text-base leading-relaxed text-text">{{ $article->excerpt }}</p>@endif
                    </article>
                @endforeach
            </div><div class="mt-8">{{ $articles->links() }}</div>
        @else
            <x-ui.empty-state title="Belum ada berita." message="Berita resmi sekolah akan tampil setelah artikel dipublikasikan." class="mt-8" />
        @endif
    </div></x-ui.container></section>
@endsection
