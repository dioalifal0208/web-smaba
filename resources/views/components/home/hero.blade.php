<section id="hero" class="bg-background py-10 md:py-14" aria-labelledby="hero-heading">
    <x-ui.container>
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(22rem,0.8fr)] lg:items-center">
            <div class="max-w-container-content">
                <h1 id="hero-heading" class="text-3xl font-bold text-text-strong md:text-4xl">
                    SMA Negeri 1 Babat
                </h1>

                <p class="mt-5 text-lg text-text">
                    Portal informasi resmi sekolah untuk pengumuman, berita, agenda, dan akses layanan digital SMA Negeri 1 Babat.
                </p>

                <nav class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap" aria-label="Akses cepat beranda">
                    <a
                        href="#pengumuman"
                        class="inline-flex min-h-11 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-semibold text-on-primary transition-colors duration-fast hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                    >
                        Pengumuman Penting
                    </a>
                    <a
                        href="#layanan-digital"
                        class="inline-flex min-h-11 items-center justify-center rounded-md border border-border px-4 py-2 text-sm font-semibold text-primary transition-colors duration-fast hover:border-primary hover:bg-primary-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                    >
                        Layanan Digital
                    </a>
                    <a
                        href="#berita"
                        class="inline-flex min-h-11 items-center justify-center rounded-md border border-border px-4 py-2 text-sm font-semibold text-primary transition-colors duration-fast hover:border-primary hover:bg-primary-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                    >
                        Berita Terbaru
                    </a>
                    <a
                        href="#agenda"
                        class="inline-flex min-h-11 items-center justify-center rounded-md border border-border px-4 py-2 text-sm font-semibold text-primary transition-colors duration-fast hover:border-primary hover:bg-primary-soft focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-focus-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                    >
                        Agenda Terdekat
                    </a>
                </nav>
            </div>

            <x-ui.placeholder-image
                ratio="video"
                label="Foto utama sekolah belum tersedia."
            />
        </div>
    </x-ui.container>
</section>
