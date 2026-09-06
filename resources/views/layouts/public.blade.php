<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', 'Beranda') — SMA Negeri 1 Babat</title>
    <meta name="description" content="@yield('description', 'Portal informasi resmi, berita, pengumuman, agenda, dan layanan digital SMA Negeri 1 Babat.')">

    {{-- Google Fonts: Plus Jakarta Sans — weight 400 500 600 700
         Dimuat di layout publik agar tersedia untuk semua halaman.
         font-display=swap mencegah invisible text saat font loading. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700&display=swap"
    >

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-background text-text antialiased">

    {{-- Skip link — DESIGN.md §32, MASTER.md §11
         Muncul hanya saat menerima fokus keyboard. --}}
    <a href="#main-content" class="skip-link">
        Langsung ke konten utama
    </a>

    {{-- Header & Navigasi --}}
    <x-layout.site-header />

    {{-- Konten utama --}}
    <main id="main-content" class="flex-1" tabindex="-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-layout.site-footer />

</body>
</html>
