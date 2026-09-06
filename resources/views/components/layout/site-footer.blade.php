{{--
    Site Footer — komponen layout publik
    DESIGN.md §28, MASTER.md §9

    Isi hanya informasi yang sudah terverifikasi atau bersifat netral.
    Tidak mencantumkan alamat, nomor telepon, email, atau media sosial
    yang belum dikonfirmasi dari CMS (site_settings).
    Tidak ada CTA komersial, newsletter, atau slogan.

    Informasi kontak dan media sosial akan ditambahkan di Phase selanjutnya
    saat modul site_settings tersedia.
--}}

<footer
    class="bg-primary-dark text-on-primary mt-auto"
    role="contentinfo"
    aria-label="Footer situs SMA Negeri 1 Babat"
>
    <x-ui.container>
        <div class="py-10 md:py-12">

            {{-- Identitas institusi --}}
            <div class="mb-6">
                <p class="text-sm font-500 opacity-70 uppercase tracking-wide mb-1">
                    Website Resmi
                </p>
                <p class="text-lg font-700 leading-tight">
                    SMA Negeri 1 Babat
                </p>
                <p class="text-sm opacity-70 mt-2 leading-relaxed max-w-sm">
                    Portal informasi resmi dan layanan digital
                    SMA Negeri 1 Babat, Lamongan, Jawa Timur.
                </p>
            </div>

            {{-- Divider --}}
            <div class="border-t border-white/10 pt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                {{-- Copyright --}}
                <p class="text-xs opacity-60">
                    &copy; {{ date('Y') }} SMA Negeri 1 Babat.
                    Hak cipta dilindungi undang-undang.
                </p>

                {{-- Admin link — komentar dikontrol DESIGN.md §38 open decision.
                     Aktifkan saat kebijakan tampil link admin disetujui. --}}
                {{-- <a href="{{ route('filament.admin.auth.login') }}" class="text-xs opacity-60 hover:opacity-90 underline transition-opacity duration-fast focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-on-primary focus-visible:ring-offset-2 focus-visible:ring-offset-primary-dark rounded-sm">
                    Masuk Admin
                </a> --}}

            </div>
        </div>
    </x-ui.container>
</footer>
