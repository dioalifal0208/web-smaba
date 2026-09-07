@extends('layouts.public')
@section('title', e($article->title))
@if (filled($article->excerpt)) @section('description', e(str(strip_tags($article->excerpt))->limit(155))) @endif
@section('content')
    <article class="bg-surface py-12 md:py-16" aria-labelledby="article-heading"><x-ui.container><div class="max-w-container-content">
        <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-primary underline underline-offset-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring">Kembali ke daftar berita</a>
        <time datetime="{{ $article->published_at->copy()->timezone('Asia/Jakarta')->toDateString() }}" class="mt-8 block text-sm text-text-muted">{{ $article->published_at->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</time>
        <h1 id="article-heading" class="mt-3 text-3xl font-bold text-text-strong md:text-4xl">{{ $article->title }}</h1>
        @if (filled($article->excerpt))<p class="mt-5 border-l-2 border-accent pl-4 text-lg text-text">{{ $article->excerpt }}</p>@endif
        <div class="mt-8 text-base leading-relaxed text-text [&_a]:text-primary [&_a]:underline [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_li]:mt-1 [&_p]:mt-4 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-6">{!! str($article->body)->sanitizeHtml() !!}</div>
    </div></x-ui.container></article>
@endsection
