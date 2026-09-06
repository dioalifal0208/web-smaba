{{--
    Homepage — skeleton Phase 2
    DESIGN.md §10, MASTER.md §2

    Phase 2: hanya memverifikasi bahwa layout publik berfungsi.
    Tidak ada hero marketing, statistik, berita, agenda, foto, atau data palsu.

    Section homepage lengkap (hero, quick access, pengumuman, layanan digital,
    berita, agenda) akan dibangun di Phase 3 saat data CMS tersedia.
--}}

@extends('layouts.public')

@section('title', 'Beranda')
@section('description', 'Portal informasi resmi, berita, pengumuman, agenda, dan layanan digital SMA Negeri 1 Babat.')

@section('content')

    {{-- ── Konten Halaman Beranda ──────────────────────────────────────────
         Phase 2: skeleton sederhana untuk memverifikasi layout.
         Tidak ada komponen homepage lengkap di tahap ini.
    ─────────────────────────────────────────────────────────────────────── --}}
    <x-ui.container class="py-16 md:py-20">

        {{-- Satu <h1> per halaman — DESIGN.md §32, MASTER.md §5 --}}
        <h1 class="text-3xl md:text-4xl font-700 text-text-strong leading-tight mb-4">
            SMA Negeri 1 Babat
        </h1>

        <p class="text-lg text-text-muted max-w-container-content leading-relaxed">
            Portal informasi resmi dan layanan digital SMA Negeri 1 Babat.
        </p>

        {{-- Status pembangunan — catatan jelas bahwa ini bukan konten final --}}
        <div class="mt-10 border border-border rounded-lg p-6 bg-surface max-w-xl">
            <p class="text-sm text-text-muted leading-relaxed">
                Halaman ini sedang dalam tahap pembangunan.
                Konten publik — berita, pengumuman, agenda, dan layanan digital —
                akan tersedia setelah pengembangan selesai.
            </p>
        </div>

    </x-ui.container>

@endsection
