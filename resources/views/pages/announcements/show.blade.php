@extends('layouts.public')
@section('title', e($announcement->title))
@if (filled($announcement->excerpt)) @section('description', e(str(strip_tags($announcement->excerpt))->limit(155))) @endif
@section('content')
    <article class="bg-surface py-12 md:py-16" aria-labelledby="announcement-heading"><x-ui.container><div class="max-w-container-content">
        <a href="{{ route('announcements.index') }}" class="text-sm font-semibold text-primary underline underline-offset-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring">Kembali ke daftar pengumuman</a>
        <time datetime="{{ $announcement->published_at->copy()->timezone('Asia/Jakarta')->toDateString() }}" class="mt-8 block text-sm text-text-muted">{{ $announcement->published_at->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</time>
        <h1 id="announcement-heading" class="mt-3 text-3xl font-bold text-text-strong md:text-4xl">{{ $announcement->title }}</h1>
        @if (filled($announcement->excerpt))<p class="mt-5 border-l-2 border-accent pl-4 text-lg text-text">{{ $announcement->excerpt }}</p>@endif
        <div class="mt-8 text-base leading-relaxed text-text [&_a]:text-primary [&_a]:underline [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_li]:mt-1 [&_p]:mt-4 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-6">{!! str($announcement->body)->sanitizeHtml() !!}</div>
    </div></x-ui.container></article>
@endsection
