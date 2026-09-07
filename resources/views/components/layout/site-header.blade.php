{{--
    Site Header — komponen layout publik
    DESIGN.md §19, MASTER.md §9

    Identitas teks digunakan karena aset logo resmi belum tersedia.
    TODO: Ganti blok identitas teks dengan <img> logo resmi saat
          file public/images/logo-sman1babat.svg tersedia dan tervalidasi.

    Navigasi:
    - Desktop: link horizontal di atas.
    - Mobile: detail/summary (native HTML, tanpa JavaScript dependency).
    - Link yang belum memiliki halaman tujuan (Phase 3+) ditandai komentar.
    - Hanya route "home" yang aktif pada Phase 2.
--}}

<header
    class="bg-primary text-on-primary shadow-xs sticky top-0 z-header"
    role="banner"
>
    <x-ui.container>
        <div class="flex items-center justify-between gap-4 h-16 md:h-[4.5rem]">

            {{-- ── Identitas Sekolah ─────────────────────────────────────────
                 Logo resmi belum tersedia. Menggunakan identitas teks.
                 Tidak membuat logo palsu atau placeholder berbentuk gambar.
            ──────────────────────────────────────────────────────────────── --}}
            <a
                href="{{ route('home') }}"
                class="flex flex-col leading-tight focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-on-primary focus-visible:ring-offset-2 focus-visible:ring-offset-primary rounded-sm"
                aria-label="SMA Negeri 1 Babat — Halaman Beranda"
            >
                <span class="text-sm font-500 tracking-wide uppercase opacity-75">
                    Website Resmi
                </span>
                <span class="text-base font-700 tracking-tight leading-tight">
                    SMA Negeri 1 Babat
                </span>
            </a>

            {{-- ── Navigasi Desktop ──────────────────────────────────────────
                 Tersembunyi di mobile (md:flex).
                 Link Phase 3+ dikomentari agar tidak muncul sebagai href palsu.
            ──────────────────────────────────────────────────────────────── --}}
            <nav
                class="hidden md:flex items-center gap-1"
                aria-label="Navigasi utama"
            >
                <x-layout.nav-link
                    :href="route('home')"
                    :active="request()->routeIs('home')"
                >
                    Beranda
                </x-layout.nav-link>
                <x-layout.nav-link :href="route('articles.index')" :active="request()->routeIs('articles.*')">Berita</x-layout.nav-link>
                <x-layout.nav-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">Pengumuman</x-layout.nav-link>

                {{-- Link berikut akan diaktifkan di Phase 3 saat route tersedia --}}
                {{-- <x-layout.nav-link href="{{ route('profile.index') }}">Profil</x-layout.nav-link> --}}
                {{-- <x-layout.nav-link href="{{ route('news.index') }}">Berita</x-layout.nav-link> --}}
                {{-- <x-layout.nav-link href="{{ route('services.index') }}">Layanan Digital</x-layout.nav-link> --}}
                {{-- <x-layout.nav-link href="{{ route('contact.index') }}">Kontak</x-layout.nav-link> --}}
            </nav>

            {{-- ── Menu Mobile (details/summary) ────────────────────────────
                 Solusi native HTML tanpa JavaScript.
                 Keyboard: Enter/Space pada <summary> membuka/menutup menu.
                 Tidak ada fokus trap — menu mengikuti alur dokumen.
            ──────────────────────────────────────────────────────────────── --}}
            <div class="md:hidden">
                <details class="relative group">
                    <summary
                        class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-600 cursor-pointer
                               list-none select-none
                               hover:bg-white/10 transition-colors duration-fast
                               focus-visible:outline-none focus-visible:ring-2
                               focus-visible:ring-on-primary focus-visible:ring-offset-2
                               focus-visible:ring-offset-primary"
                        aria-label="Buka menu navigasi"
                    >
                        {{-- Hamburger icon — SVG inline, aria-hidden karena label ada di aria-label summary --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                            focusable="false"
                        >
                            {{-- Tiga garis hamburger --}}
                            <line x1="3" y1="6"  x2="21" y2="6"  />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                        <span class="sr-only">Menu</span>
                    </summary>

                    {{-- Panel menu mobile --}}
                    <nav
                        class="absolute right-0 top-full mt-1 w-52
                               bg-primary-dark border border-white/10
                               rounded-lg shadow-sm py-1
                               z-dropdown"
                        aria-label="Menu navigasi mobile"
                    >
                        <a
                            href="{{ route('home') }}"
                            class="block px-4 py-3 text-sm font-500 text-on-primary
                                   hover:bg-white/10 transition-colors duration-fast
                                   focus-visible:outline-none focus-visible:bg-white/10
                                   {{ request()->routeIs('home') ? 'font-700 bg-white/5' : '' }}"
                            @if(request()->routeIs('home')) aria-current="page" @endif
                        >
                            Beranda
                        </a>

                        <a href="{{ route('articles.index') }}" class="block px-4 py-3 text-sm font-500 text-on-primary hover:bg-white/10 transition-colors duration-fast focus-visible:outline-none focus-visible:bg-white/10 {{ request()->routeIs('articles.*') ? 'font-700 bg-white/5' : '' }}" @if(request()->routeIs('articles.*')) aria-current="page" @endif>Berita</a>
                        <a href="{{ route('announcements.index') }}" class="block px-4 py-3 text-sm font-500 text-on-primary hover:bg-white/10 transition-colors duration-fast focus-visible:outline-none focus-visible:bg-white/10 {{ request()->routeIs('announcements.*') ? 'font-700 bg-white/5' : '' }}" @if(request()->routeIs('announcements.*')) aria-current="page" @endif>Pengumuman</a>

                        {{-- Link Phase 3+ dikomentari --}}
                        {{-- <a href="{{ route('profile.index') }}" …>Profil</a> --}}
                        {{-- <a href="{{ route('news.index') }}" …>Berita</a> --}}
                        {{-- <a href="{{ route('services.index') }}" …>Layanan Digital</a> --}}
                        {{-- <a href="{{ route('contact.index') }}" …>Kontak</a> --}}
                    </nav>
                </details>
            </div>

        </div>
    </x-ui.container>
</header>
