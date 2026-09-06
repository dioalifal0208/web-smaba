# DESIGN.md

## 1. Document status

Status dokumen: Draft desain V1.

Peran dokumen: pedoman produk, konten, dan visual untuk website resmi SMA Negeri 1 Babat.

Sumber utama:

- `docs/PRD.md` untuk scope produk, fitur, arsitektur, dan Definition of Done.
- `docs/ARCHITECTURE.md` dan `docs/DATABASE.md` untuk batas teknis V1.
- `design-system/sman-1-babat/MASTER.md` untuk token teknis warna, tipografi, spacing, radius, shadow, dan interaksi.
- `.agents/skills/ui-ux-pro-max/SKILL.md` untuk prinsip UI/UX, aksesibilitas, responsive, form, navigasi, performa, dan anti-pattern.

Keputusan dalam dokumen ini dibedakan menjadi:

- Final: sudah boleh dipakai sebagai aturan desain V1.
- Provisional: boleh dipakai sementara, tetapi harus divalidasi sebelum production.
- Open decision: belum boleh dikunci sebagai keputusan final.

Jika ada konflik antara dokumen, konflik tersebut harus dicatat dan diselesaikan melalui persetujuan eksplisit. Keputusan terbaru yang disetujui dalam brief pekerjaan ini mengikat dokumen ini.

## 2. Tujuan dokumen

Dokumen ini bertujuan memberi pedoman yang dapat dibaca manusia untuk merancang dan mengimplementasikan tampilan publik website SMA Negeri 1 Babat secara konsisten.

Dokumen ini tidak menggantikan `MASTER.md`. `MASTER.md` tetap menjadi sumber token teknis. `DESIGN.md` menjelaskan cara memakai token tersebut dalam konteks produk, konten, komponen, dan pengalaman pengguna.

Dokumen ini tidak memberi izin untuk melakukan implementasi frontend, perubahan route, perubahan database, pemasangan package, atau perubahan file lain.

## 3. Konteks produk

Website SMA Negeri 1 Babat adalah portal informasi resmi dan pusat layanan digital sekolah. Website ini menggantikan website lama berbasis WordPress dengan aplikasi Laravel 11 yang server-rendered, responsif, mudah dipelihara, dan kompatibel dengan Hostinger.

Karakter produk adalah portal pendidikan publik, bukan produk SaaS, landing page komersial, blog template, atau microsite promosi. Pengguna datang untuk mencari informasi resmi, membaca pengumuman, mengikuti agenda, mengakses layanan digital, melihat profil sekolah, dan menemukan kontak.

Konten yang berubah secara operasional harus CMS-driven. Template publik tidak boleh mengandung data sekolah yang dibuat-buat atau hardcoded jika data tersebut seharusnya dikelola admin.

## 4. Tujuan website

Website harus menjadi satu pintu resmi untuk:

- informasi profil sekolah;
- berita dan publikasi;
- pengumuman;
- agenda;
- prestasi;
- galeri kegiatan;
- dokumen publik;
- direktori guru dan tenaga kependidikan;
- kontak;
- akses layanan digital dan subdomain sekolah.

Keberhasilan desain diukur dari kemudahan menemukan informasi, kejelasan identitas resmi sekolah, keterbacaan di perangkat mobile, aksesibilitas, dan konsistensi visual.

## 5. Kelompok pengguna

Kelompok pengguna utama:

- Siswa: membutuhkan pengumuman, agenda, berita, dan akses layanan digital.
- Orang tua atau wali: membutuhkan informasi resmi, pengumuman, agenda, profil, prestasi, SPMB, dan kontak.
- Guru: membutuhkan pengumuman, agenda, berita, layanan guru, dan dokumen.
- Tenaga kependidikan: membutuhkan informasi sekolah, layanan digital, pengumuman, dan agenda.
- Calon peserta didik: membutuhkan profil, fasilitas, prestasi, kegiatan, SPMB, dan kontak.
- Masyarakat umum: membutuhkan profil resmi, berita, galeri, prestasi, dan kontak.
- Admin website: membutuhkan CMS yang memungkinkan pembaruan konten tanpa mengubah source code.

Desain publik harus memprioritaskan kebutuhan pembaca umum dan mobile. Admin UI mengikuti Filament dan tidak menjadi ruang lingkup desain publik dokumen ini.

## 6. Prinsip desain

Prinsip final:

- Official first: identitas sekolah, konteks resmi, dan navigasi harus jelas sejak awal.
- Information hierarchy over persuasion: informasi penting didahulukan dibanding copy promosi.
- Editorial clarity: gunakan pola berita, daftar, baris, tanggal, metadata, dan ringkasan yang mudah dipindai.
- Human but restrained: tampilkan kehidupan sekolah melalui foto autentik, bukan efek dekoratif.
- Mobile-first: layout 375px harus nyaman, tanpa horizontal overflow.
- Accessible by default: target WCAG AA untuk kontras, heading, keyboard, form, dan alternative text.
- CMS-driven: konten aktual, statistik, media, banner, pengumuman, dan layanan dikelola admin.
- Hostinger-compatible: desain harus ringan, server-rendered, dan tidak bergantung pada stack di luar PRD.

## 7. Karakter dan arah visual

Arah visual final: Modern Institutional Editorial.

Karakter yang harus terasa:

- resmi;
- bersih;
- kredibel;
- rapi;
- informatif;
- manusiawi;
- tidak berlebihan.

Karakter yang harus dihindari:

- SaaS dashboard;
- startup landing page;
- desain agensi generik;
- promosi komersial;
- visual terlalu dekoratif;
- efek futuristik yang tidak terkait sekolah.

Visual utama harus datang dari struktur informasi, tipografi, whitespace, foto autentik, palet forest-green, warm-neutral, dan muted-gold yang terkendali, serta komponen editorial.

## 8. Hierarki informasi

Prioritas informasi homepage:

1. Identitas resmi SMA Negeri 1 Babat.
2. Pengumuman penting.
3. Layanan Digital.
4. Berita terbaru.
5. Agenda terdekat.
6. Prestasi terbaru jika data valid tersedia.
7. Profil singkat atau sambutan jika konten sudah tersedia.
8. Galeri kegiatan jika foto autentik tersedia.
9. Footer berisi informasi institusi, kontak, dan navigasi.

Catatan konflik:

- PRD mencantumkan Statistik Sekolah sebagai bagian homepage dan modul admin.
- Keputusan terbaru yang disetujui melarang statistik sekolah pada homepage V1 sebelum tersedia data CMS yang valid dan terverifikasi.
- Resolusi dokumentasi untuk V1: statistik tidak ditampilkan di homepage V1 sampai data CMS valid tersedia dan disetujui.

## 9. Information architecture

Navigasi utama mengikuti PRD dan MASTER:

- Beranda
- Profil
- Akademik
- Kesiswaan
- Berita
- Layanan Digital
- Galeri
- Kontak

Substruktur yang direncanakan:

- Profil: Sejarah Sekolah, Visi dan Misi, Sambutan Kepala Sekolah, Struktur Organisasi, Guru dan Tenaga Kependidikan, Sarana dan Prasarana.
- Akademik: Kurikulum, Kalender Pendidikan, Prestasi Akademik, Dokumen Akademik.
- Kesiswaan: OSIS, Ekstrakurikuler, Prestasi Siswa, Bimbingan Konseling, Tata Tertib.
- Berita: Semua Berita, Kategori Berita, Detail Berita.
- Pengumuman: Daftar Pengumuman, Detail Pengumuman.
- Agenda: Daftar Agenda, Detail Agenda.
- Prestasi: Prestasi Siswa, Prestasi Guru, Prestasi Sekolah.
- Galeri: Daftar Album, Detail Album.
- Dokumen: Dokumen Publik, Surat Edaran, Kalender Pendidikan, Unduhan.
- Layanan Digital: Semua Layanan, filter berdasarkan kategori.
- Kontak: Alamat, telepon, email, peta, media sosial.

Setiap halaman detail yang berada lebih dari dua level dari homepage harus memiliki orientasi jelas, seperti breadcrumb atau heading konteks.

## 10. Struktur homepage

Struktur homepage V1 yang disetujui:

1. Utility bar jika data kontak atau link penting tersedia.
2. Header identitas dan navigasi utama.
3. Hero berbasis foto autentik sekolah atau placeholder netral berlabel.
4. Quick access untuk tautan berfrekuensi tinggi.
5. Pengumuman penting.
6. Layanan Digital.
7. Berita terbaru.
8. Agenda terdekat.
9. Prestasi terbaru jika data valid tersedia.
10. Profil singkat atau sambutan jika konten valid tersedia.
11. Galeri preview jika foto autentik tersedia.
12. Footer.

Aturan:

- Hero tidak boleh terlalu tinggi sampai pengumuman dan layanan terdorong jauh ke bawah.
- CTA hero bersifat navigasi praktis, seperti Profil Sekolah, Layanan Digital, Berita, atau Pengumuman.
- Tidak ada statistik homepage V1 sebelum data CMS valid dan terverifikasi.
- Jika foto, profil, prestasi, atau galeri belum tersedia, tampilkan empty state atau placeholder berlabel, bukan konten palsu.

## 11. Sistem navigasi

Navigasi harus text-first, stabil, dan mudah dipindai.

Aturan final:

- Header memuat logo, nama sekolah, konteks resmi, dan navigasi utama.
- Tidak menggunakan icon-only navigation untuk menu utama.
- Current page state harus terlihat melalui weight, underline, border, atau background.
- Mobile navigation harus dapat dibuka, ditutup, dioperasikan dengan keyboard, dan tidak menjebak fokus.
- Dropdown boleh digunakan untuk Profil, Akademik, atau Kesiswaan, tetapi jumlah item harus tetap terkendali.
- Link eksternal layanan digital harus diberi affordance teks atau ikon yang jelas.
- Navigasi tidak boleh berubah lokasi secara tidak konsisten antar halaman.

## 12. Sistem warna dan fungsi setiap warna

Status: approved direction untuk arah palet, provisional untuk nilai hex sampai tersedia logo resmi beresolusi tinggi atau format vector.

Warna harus memakai token dari `MASTER.md`.

Token utama:

| Peran | Token | Nilai | Fungsi |
|---|---|---:|---|
| Primary | `--color-primary` | `#245C3A` | Header, button primary, active nav, link utama, identitas resmi, focus ring |
| On Primary | `--color-on-primary` | `#FFFFFF` | Teks dan ikon di atas primary forest green |
| Primary Dark | `--color-primary-dark` | `#183F29` | Footer, section institusional gelap, heading atau elemen kontras tinggi jika diperlukan |
| Primary Soft | `--color-primary-soft` | `#EAF2E7` | Background green lembut dan selected state |
| Secondary | `--color-secondary` | `#4F7C37` | Aksen pendukung, status aktif, detail kategori, elemen visual kecil |
| Logo Green | `--color-logo-green` | `#82BD24` | Aksen kecil yang terinspirasi logo; bukan background besar, body text, atau button utama |
| Accent | `--color-accent` | `#C5A62D` | Muted-gold terbatas untuk marker, underline, divider, badge, highlight institusional |
| Accent Soft | `--color-accent-soft` | `#F7F0D2` | Background pengumuman penting atau highlight institusional rendah |
| Background | `--color-background` | `#FAF9F4` | Background warm ivory utama |
| Surface | `--color-surface` | `#FFFFFF` | Area baca dan komponen yang membutuhkan kontras dari background |
| Surface Muted | `--color-surface-muted` | `#F1F5EE` | Section band netral dan permukaan grup yang tenang |
| Text Strong | `--color-text-strong` | `#1B2820` | Judul, teks prioritas, teks di atas muted-gold |
| Text | `--color-text` | `#35443A` | Body copy |
| Text Muted | `--color-text-muted` | `#5D6B61` | Metadata dan helper text |
| Border | `--color-border` | `#D5DFD2` | Divider, input, table |
| Border Strong | `--color-border-strong` | `#AABAAA` | Separator kuat dan state penting |
| Focus Ring | `--color-focus-ring` | `#245C3A` | Focus-visible |

Catatan status:

- Palet forest-green, warm-neutral, dan muted-gold adalah approved direction.
- Nilai hex masih provisional sampai tersedia logo resmi beresolusi tinggi atau format vector.
- Warna final harus divalidasi terhadap logo resmi atau panduan identitas resmi sekolah.
- `--color-logo-green` hanya digunakan sebagai aksen kecil, tidak untuk background section besar, body text, atau button utama.
- `--color-accent` bukan warna CTA utama. Gunakan `--color-text-strong` untuk teks di atas `--color-accent` atau `--color-accent-soft`, bukan teks putih.
- Merah dari logo tidak digunakan sebagai brand color antarmuka. Merah hanya digunakan sebagai semantic error atau danger.
- Biru tua pada logo tetap menjadi bagian aset logo, tetapi tidak digunakan sebagai primary atau secondary UI color.

Aturan uji:

- Teks normal harus mencapai kontras WCAG AA 4.5:1.
- Meaningful icon, border kontrol, dan state harus mencapai kontras non-teks minimal 3:1.
- Status tidak boleh disampaikan melalui warna saja.
- Raw hex tidak boleh diulang langsung di komponen bila token tersedia.
- Jangan mengubah warna yang terdapat di dalam file logo.

## 13. Sistem tipografi

Status: final untuk V1.

Font utama:

`"Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`

Aturan:

- Gunakan Plus Jakarta Sans sebagai font utama dengan system fallback.
- Belum perlu font kedua.
- Hierarchy editorial dibentuk melalui ukuran, weight, line-height, spacing, dan posisi, bukan melalui banyak font.
- Load hanya weight yang diperlukan: 400, 500, 600, 700.
- Gunakan `font-display: swap` atau perilaku setara.
- Body publik minimal 16px.
- Jangan gunakan negative letter spacing.

Skala dari `MASTER.md`:

| Token | Ukuran | Line height | Fungsi |
|---|---:|---:|---|
| `--text-xs` | 12px | 16px | Metadata ringkas |
| `--text-sm` | 14px | 20px | Label, nav sekunder |
| `--text-base` | 16px | 26px | Body publik |
| `--text-lg` | 18px | 28px | Lead paragraph |
| `--text-xl` | 20px | 30px | Judul item |
| `--text-2xl` | 24px | 32px | Heading section |
| `--text-3xl` | 30px | 38px | Page title atau hero mobile |
| `--text-4xl` | 36px | 44px | Hero desktop |

## 14. Grid, container, breakpoint, dan spacing

Gunakan sistem 4px/8px dari `MASTER.md`.

Container:

- `--container-page`: 1280px untuk layout publik utama.
- `--container-content`: 768px untuk artikel dan long-form.
- `--container-narrow`: 672px untuk form atau teks fokus.
- `--container-wide`: 1440px untuk layout media khusus.

Gutter:

- Mobile: 16px.
- Tablet: 24px.
- Desktop: 32px.

Breakpoint uji:

- 375px: small phone.
- 768px: tablet.
- 1024px: laptop.
- 1440px: desktop.

Aturan:

- Mobile-first adalah baseline.
- Tidak boleh ada horizontal overflow di 375px.
- Grid desktop boleh dipakai jika mempercepat scanning.
- Pada mobile, daftar vertikal lebih diutamakan daripada grid.
- Long-form prose harus berada pada lebar baca sekitar 60-75 karakter di desktop.

## 15. Border, radius, dan shadow

Status: final untuk V1.

Aturan:

- Gunakan border dan perbedaan background sebelum shadow.
- Radius umum maksimal 8px.
- `--radius-sm` 4px untuk label atau badge.
- `--radius-md` 6px untuk input dan thumbnail.
- `--radius-lg` 8px untuk kartu, panel, gambar, dan tombol.
- Shadow default adalah none.
- `--shadow-xs` hanya untuk header atau surface subtle.
- `--shadow-sm` hanya untuk dropdown atau feature media tertentu.

Dilarang:

- radius kapsul besar sebagai dekorasi;
- shadow berat;
- glow;
- glassmorphism;
- backdrop blur;
- kartu bersarang;
- semua section dibentuk sebagai kartu.

## 16. Iconography

Ikon bersifat fungsional, bukan dekoratif.

Aturan:

- Gunakan satu keluarga ikon SVG yang konsisten bila ikon diperlukan.
- Dalam konteks Blade/Laravel, preferensi implementasi berikutnya adalah icon set yang sudah tersedia atau kompatibel dengan stack, misalnya Heroicons dari ekosistem Blade jika sudah ada.
- Jangan memakai emoji sebagai ikon struktural.
- Ikon pada tombol icon-only harus memiliki accessible name.
- Ikon dekoratif di samping teks terlihat harus disembunyikan dari assistive technology.
- Jangan menaruh ikon dekoratif di setiap heading.
- Ikon layanan digital harus membantu membedakan kategori atau fungsi, bukan menjadi ornamen.

Ukuran harus konsisten dan tidak memakai nilai acak.

## 17. Kebijakan foto dan media

Status: final untuk V1.

Aturan utama:

- Hanya gunakan foto autentik SMA Negeri 1 Babat.
- Foto stok generik tidak boleh digunakan sebagai representasi aktivitas sekolah.
- Gambar AI tidak boleh digunakan sebagai dokumentasi atau representasi aktivitas sekolah.
- Jika foto belum tersedia, gunakan placeholder netral yang diberi label jelas, misalnya "Placeholder foto sekolah - belum ada media resmi".
- Placeholder tidak boleh dipresentasikan sebagai konten nyata.
- Hero harus memakai foto autentik yang masih memperlihatkan konteks sekolah.
- Foto informatif wajib memiliki alt text deskriptif.
- Foto dekoratif memakai alt kosong.
- Semua gambar harus memiliki dimensi atau aspect-ratio untuk mencegah layout shift.
- Gambar non-prioritas harus lazy-loaded.
- Media besar harus dioptimalkan dan mendukung cloud media storage sesuai PRD.

Rasio:

- Hero: 16:9 atau lebih lebar.
- Thumbnail berita: 16:9.
- Cover galeri: 4:3 atau 3:2.
- Foto GTK: 4:5.
- Foto prestasi: 4:3 atau 16:9.

## 18. Pola komponen

Gunakan pola komponen editorial, bukan template kartu generik.

Pola yang disetujui:

- Editorial list untuk berita.
- Announcement list untuk pengumuman.
- Date-led row untuk agenda.
- Service link atau service row untuk layanan digital.
- Media feature untuk konten utama.
- Section band untuk pemisahan area.
- Public information list atau table untuk dokumen dan direktori.
- Gallery album grid hanya ketika foto autentik tersedia.

Aturan:

- Card hanya untuk item berulang yang perlu boundary.
- Jangan membuat semua section sebagai floating card.
- Component menerima data dari controller, bukan melakukan query sendiri.
- Empty state harus tersedia untuk setiap collection.

## 19. Header dan utility bar

Utility bar boleh tampil jika data valid tersedia:

- kontak sekolah;
- jam pelayanan;
- link media sosial resmi;
- link admin/login;
- link layanan penting.

Header harus memuat:

- logo resmi jika sudah tersedia;
- nama SMA Negeri 1 Babat;
- konteks sebagai website resmi;
- navigasi utama;
- tombol menu mobile dengan label aksesibilitas.

Aturan:

- Header sticky boleh dipertimbangkan, tetapi focus-visible tidak boleh tertutup.
- Utility bar tidak boleh berisi terlalu banyak item.
- Nama sekolah harus lebih kuat daripada slogan atau copy promosi.
- Logo tidak boleh direka ulang tanpa aset resmi.

## 20. Button dan link

Button dipakai untuk aksi atau navigasi yang jelas. Link inline tetap terlihat sebagai link.

Varian:

- Primary: forest-green fill, white text, untuk navigasi utama yang praktis.
- Secondary: border atau text link, untuk aksi pendukung.
- Tertiary: text link dengan underline atau state jelas.
- Danger: hanya untuk aksi destruktif di konteks admin atau form, bukan homepage publik.

Aturan:

- Muted-gold tidak dipakai sebagai button primary fill.
- Label harus menjelaskan tujuan, bukan "Klik di sini".
- Touch target praktis minimal 44px.
- Hover, focus-visible, active, loading, dan disabled state harus jelas.
- Tombol disabled harus benar-benar non-interaktif secara semantik.

## 21. Pengumuman

Pengumuman penting adalah konten prioritas homepage.

Data yang boleh tampil:

- judul;
- tanggal mulai dan akhir jika tersedia;
- ringkasan;
- status penting;
- lampiran jika ada;
- tautan detail.

Aturan:

- Gunakan `--color-accent-soft` dan border kiri `--color-accent` secara terbatas untuk pengumuman penting.
- Jangan menyampaikan urgensi hanya dengan warna.
- Pengumuman kedaluwarsa boleh tetap ada sebagai arsip dengan label yang jelas.
- Jika tidak ada pengumuman, tampilkan empty state netral tanpa mengarang informasi.

## 22. Berita dan artikel

Berita adalah permukaan editorial utama.

Pola:

- judul;
- ringkasan;
- tanggal publikasi;
- kategori;
- penulis jika tersedia;
- thumbnail autentik opsional;
- pagination pada daftar.

Aturan:

- Berita publik hanya menampilkan status published.
- Thumbnail boleh kosong atau placeholder berlabel jika foto resmi belum tersedia.
- Judul panjang harus wrap tanpa merusak layout.
- Detail berita memakai lebar baca terkontrol.
- Related news tidak boleh muncul jika data tidak cukup.
- Jangan membuat isi artikel palsu untuk mengisi layout.

## 23. Agenda

Agenda memakai pola date-led row agar tanggal mudah dipindai.

Data yang boleh tampil:

- tanggal mulai;
- tanggal selesai jika ada;
- waktu jika tersedia;
- lokasi jika tersedia;
- kategori;
- status;
- tautan detail.

Aturan:

- Agenda terdekat diprioritaskan.
- Agenda yang sudah lewat diberi state arsip atau selesai.
- Waktu dan lokasi boleh kosong jika memang belum tersedia, tetapi UI harus tetap rapi.
- Empty state wajib tersedia.

## 24. Layanan digital

Layanan Digital adalah gateway resmi ke subdomain dan aplikasi sekolah.

Data yang boleh tampil:

- nama layanan;
- deskripsi singkat;
- URL;
- kategori;
- status;
- jenis akses;
- affordance eksternal jika keluar domain utama.

Aturan:

- Jangan desain sebagai SaaS feature grid.
- Tampilkan status dalam teks: Aktif, Maintenance, Musiman, Pengembangan, atau Arsip.
- Tampilkan jenis akses dalam teks: Publik, Internal, Publik Terbatas, Khusus Guru, Khusus Siswa, atau Khusus Admin.
- Link eksternal harus jelas dan tetap dapat digunakan tanpa hover.
- Layanan di homepage hanya yang ditandai tampil di homepage oleh CMS.

## 25. Prestasi

Prestasi boleh tampil jika data valid tersedia.

Data yang boleh tampil:

- judul;
- kategori;
- tingkat;
- peserta atau pihak terkait jika memang tercatat;
- tahun atau tanggal;
- foto autentik jika tersedia;
- tautan detail.

Aturan:

- Jangan membuat angka, klaim, level, nama lomba, atau nama peserta palsu.
- Jika belum ada data valid, tampilkan empty state atau sembunyikan section sesuai keputusan produk.
- Prestasi harus dibaca sebagai publikasi sekolah, bukan social proof komersial.

## 26. Direktori guru dan karyawan

Direktori GTK harus informatif dan menjaga privasi.

Data publik yang boleh tampil:

- foto;
- nama;
- gelar;
- jabatan;
- mata pelajaran atau unit kerja;
- kategori;
- status aktif jika dibutuhkan.

Data yang tidak boleh tampil:

- NIK;
- alamat rumah;
- nomor telepon pribadi;
- dokumen pegawai;
- data sensitif lainnya.

Aturan visual:

- Foto memakai rasio 4:5.
- Direktori mobile dapat memakai list atau stacked card.
- Filter kategori boleh digunakan jika data cukup.
- Empty state harus netral dan tidak menyiratkan data hilang sebagai kesalahan pengguna.

## 27. Galeri

Galeri berbasis album dan hanya boleh memakai foto autentik.

Data yang boleh tampil:

- judul album;
- deskripsi;
- kategori;
- tanggal kegiatan;
- cover autentik;
- caption foto jika tersedia.

Aturan:

- Jangan gunakan stock photo sebagai cover album.
- Jangan gunakan gambar AI sebagai dokumentasi kegiatan.
- Album tanpa cover resmi memakai placeholder netral berlabel.
- Layout harus menjaga aspect-ratio dan mencegah layout shift.
- Lightbox jika nanti dibuat harus dapat ditutup dengan keyboard dan menghormati reduced motion.

## 28. Footer

Footer adalah penutup institusional, bukan area promosi.

Isi yang disarankan jika datanya valid:

- nama sekolah;
- alamat;
- telepon;
- email;
- tautan media sosial resmi;
- navigasi ringkas;
- tautan layanan digital;
- informasi hak cipta;
- tautan admin jika disetujui.

Aturan:

- Footer boleh memakai primary dark forest green, yaitu `--color-primary-dark`, dengan teks kontras tinggi.
- Jangan menaruh slogan palsu atau klaim yang tidak ada di CMS.
- Link harus dapat dipindai dan memiliki focus state.

## 29. Form dan validasi

Form publik V1 terutama untuk pencarian, filter, dan kontak jika nanti diaktifkan.

Aturan:

- Setiap input memiliki label terlihat.
- Placeholder bukan pengganti label.
- Text input minimal 16px.
- Error tampil dekat field terkait.
- Error menjelaskan masalah dan cara memperbaiki.
- Setelah submit gagal dengan banyak error, fokus diarahkan ke error summary atau field error pertama.
- Loading state mencegah submit ganda.
- Success state mengonfirmasi hasil tanpa menghilangkan konteks.
- Field required ditandai secara teks, bukan warna saja.

## 30. Loading, empty, error, dan success state

Setiap modul collection harus memiliki state yang jelas.

Loading:

- gunakan skeleton sederhana atau teks status untuk proses lebih dari satu detik;
- jangan memakai animasi dekoratif;
- reserve space agar layout tidak meloncat.

Empty:

- jelaskan bahwa konten belum tersedia atau belum dipublikasikan;
- jangan menyalahkan pengguna;
- jangan mengarang konten.

Error:

- jelaskan masalah dalam bahasa manusia;
- sediakan recovery path seperti ulangi, kembali, atau hubungi admin jika relevan;
- error tidak boleh menampilkan stack trace.

Success:

- tampilkan konfirmasi singkat;
- jangan memakai efek berlebihan;
- tidak mengubah layout secara mendadak.

## 31. Responsive behavior

Aturan uji:

- Verifikasi minimal pada 375px, 768px, 1024px, dan 1440px.
- Tidak ada horizontal overflow.
- Header mobile dapat digunakan dengan touch dan keyboard.
- Konten prioritas muncul lebih awal di mobile: pengumuman, layanan digital, berita, agenda.
- Grid desktop menjadi list atau stacked layout di mobile.
- Teks panjang, URL, nama layanan, dan judul artikel harus wrap.
- Gambar menjaga aspect-ratio.
- Sticky element tidak menutup fokus keyboard.

Mobile bukan versi desktop yang diperkecil. Urutan konten mobile harus mengikuti kebutuhan informasi pengguna mobile.

## 32. Accessibility WCAG AA

Target: WCAG AA sejauh layak untuk V1.

Checklist:

- Satu `h1` per halaman.
- Heading hierarchy berurutan.
- Gunakan semantic HTML: `header`, `nav`, `main`, `section`, `article`, `aside`, `footer`, `time`, `figure`, dan `figcaption` jika relevan.
- Skip link tersedia.
- Kontras teks normal minimal 4.5:1.
- Kontras teks besar minimal 3:1.
- Kontras non-teks untuk meaningful icon dan control boundary minimal 3:1.
- Semua link dan tombol memiliki focus-visible.
- Menu mobile dapat dioperasikan keyboard.
- Icon-only control memiliki accessible name.
- Foto informatif memiliki alt text.
- Ikon dekoratif disembunyikan dari assistive technology.
- Informasi tidak bergantung pada warna saja.
- Form memakai label, helper text, error inline, dan error summary bila diperlukan.
- Auto-rotating content dihindari; jika nanti dipakai, harus ada pause/stop.

## 33. Motion dan interaction

Motion bersifat minimal dan fungsional.

Allowed:

- transisi hover dan focus;
- perubahan background, border, color, atau opacity;
- buka/tutup menu atau disclosure dalam durasi `--duration-base`;
- feedback loading sederhana.

Forbidden:

- GSAP;
- ScrollTrigger;
- scroll reveal sebagai pola baseline;
- parallax;
- animated decorative background;
- glow pulse;
- animasi dekoratif;
- layout-shifting hover;
- animasi yang menyembunyikan konten penting.

Aturan:

- Gunakan durasi dari `MASTER.md`: 120ms, 180ms, dan 240ms sesuai konteks.
- Respect `prefers-reduced-motion`.
- Interaksi tidak boleh bergantung pada hover saja.

## 34. Content design dan gaya bahasa

Bahasa harus formal, jelas, dan langsung.

Aturan:

- Gunakan Bahasa Indonesia baku.
- Tulis sebagai institusi sekolah, bukan brand komersial.
- Hindari copy promosi generik.
- Judul harus menjelaskan isi, bukan sekadar menarik perhatian.
- Ringkasan berita harus faktual dan tidak berlebihan.
- CTA harus praktis: Lihat Profil Sekolah, Buka Layanan Digital, Baca Pengumuman, Lihat Agenda.
- Jangan membuat slogan, testimoni, statistik, nama, atau data palsu.
- Konten yang belum valid harus diberi label status, bukan dipoles sebagai konten nyata.

## 35. Anti-AI-slop

Website wajib menghindari:

- layout SaaS;
- hero dengan copy promosi generik;
- gradient ungu-merah muda;
- gradient ungu-merah;
- glassmorphism;
- backdrop blur dekoratif;
- decorative glow;
- bento grid generik;
- semua section berbentuk kartu;
- kartu bersarang;
- pill dan badge berlebihan;
- ikon dekoratif pada setiap heading;
- dekorasi icon bubble;
- radius berlebihan;
- shadow berlebihan;
- statistik palsu;
- testimoni palsu;
- social proof komersial;
- pricing section;
- sticky conversion CTA;
- foto stok generik;
- gambar AI sebagai dokumentasi sekolah;
- animasi dekoratif;
- parallax;
- placeholder yang dianggap sebagai data nyata.

Jika ragu, pilih tampilan editorial institusional yang lebih tenang.

## 36. Visual QA checklist

Sebelum desain publik dianggap siap:

- Identitas SMA Negeri 1 Babat jelas pada first viewport.
- Homepage tidak terlihat seperti SaaS, startup, atau landing page komersial.
- Warna memakai token dari `MASTER.md`.
- Forest-green, warm-neutral, white surface, dan muted-gold digunakan terkendali.
- Muted-gold tidak menjadi CTA utama.
- Font utama Plus Jakarta Sans dan fallback sistem.
- Body minimal 16px.
- Tidak ada statistik homepage sebelum data CMS valid.
- Tidak ada konten, foto, testimoni, atau angka palsu.
- Foto sekolah autentik atau placeholder netral berlabel.
- Layout 375px tidak horizontal overflow.
- Header mobile dapat dipakai.
- Touch target praktis minimal 44px.
- Semua link dan tombol punya focus-visible.
- Section tidak semuanya berbentuk kartu.
- Radius tidak lebih dari 8px untuk surface normal.
- Shadow digunakan sangat terbatas.
- Empty, loading, error, dan success state tersedia.
- Motion minimal dan menghormati reduced motion.
- Semantic HTML dan heading hierarchy benar.
- Kontras memenuhi WCAG AA.

## 37. Implementation order

Urutan ini adalah pedoman untuk pekerjaan berikutnya, bukan implementasi dalam langkah ini.

1. Validasi aset identitas: logo, warna resmi, favicon, dan foto autentik awal.
2. Siapkan token Tailwind/CSS dari `MASTER.md` tanpa mengubah arah stack.
3. Buat layout publik dasar: skip link, header, nav, main, footer.
4. Buat komponen editorial dasar: section heading, button/link, placeholder media, empty state.
5. Bangun homepage secara bertahap sesuai prioritas: hero, quick access, pengumuman, layanan digital, berita, agenda.
6. Tambahkan halaman collection: berita, pengumuman, agenda, layanan digital.
7. Tambahkan halaman profil, GTK, prestasi, galeri, dokumen, dan kontak sesuai data CMS.
8. Lakukan visual QA pada 375px, 768px, 1024px, dan 1440px.
9. Lakukan accessibility QA: keyboard, focus, contrast, alt text, heading.
10. Baru evaluasi statistik homepage setelah data CMS valid dan terverifikasi tersedia.

## 38. Open decisions

Final:

- Website memakai karakter Modern Institutional Editorial.
- Stack tetap Laravel 11, Blade, Tailwind CSS, Filament, MySQL, media cloud, Hostinger.
- Plus Jakarta Sans menjadi font utama dengan system fallback.
- Foto stok generik dan gambar AI tidak boleh menjadi representasi aktivitas sekolah.
- Statistik tidak tampil di homepage V1 sebelum data CMS valid dan terverifikasi.
- Tidak ada statistik, slogan, testimoni, nama, atau data palsu.
- Motion minimal dan fungsional.
- Radius umum maksimal 8px.
- `MASTER.md` menjadi sumber token teknis.

Provisional:

- Palet forest-green, warm-neutral, dan muted-gold dari `MASTER.md` menjadi approved direction.
- Nilai hex masih provisional sampai tersedia logo resmi beresolusi tinggi atau format vector.
- Warna final harus divalidasi terhadap logo resmi atau panduan identitas resmi sekolah.
- Hero memakai foto autentik sekolah; jika belum tersedia, gunakan placeholder netral berlabel.
- Icon set final mengikuti ketersediaan stack dan aset yang disetujui, dengan syarat konsisten dan SVG.

Open decision:

- Aset logo resmi final dan aturan clear space.
- Validasi warna terhadap identitas resmi sekolah.
- Foto autentik mana yang boleh menjadi hero pertama.
- Apakah utility bar selalu tampil atau hanya saat data kontak lengkap.
- Apakah homepage menyertakan sambutan kepala sekolah pada V1 awal atau fase berikutnya.
- Apakah search global masuk V1 awal atau ditunda sesuai PRD.
- Kapan statistik homepage boleh diaktifkan setelah data CMS valid tersedia.
- Apakah link admin tampil di footer, utility bar, atau tidak ditampilkan di publik.
- Kebijakan detail lightbox galeri bila galeri sudah diimplementasikan.
