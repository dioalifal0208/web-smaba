# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Website Resmi SMA Negeri 1 Babat

**Versi:** 1.0  
**Status:** Draft Baseline  
**Target:** Website Production  
**Platform:** Web Responsive  
**Domain Utama:** `smanegeri1babatlmg.sch.id`  
**Hosting:** Hostinger  
**Backend Framework:** Laravel 11  
**PHP:** 8.2+  

---

# 1. Ringkasan Produk

Website baru SMA Negeri 1 Babat merupakan pembangunan ulang website utama sekolah untuk menggantikan website lama berbasis WordPress.

Website dirancang sebagai:

> **Portal Informasi Resmi dan Pusat Layanan Digital SMA Negeri 1 Babat.**

Website harus berfungsi sebagai pusat informasi publik sekolah sekaligus gateway menuju berbagai aplikasi dan layanan digital yang telah tersedia pada subdomain sekolah.

Website harus:

- modern;
- responsif;
- cepat;
- mudah dipelihara;
- mudah diperbarui;
- aman;
- modular;
- kompatibel dengan infrastruktur Hostinger;
- dapat dikembangkan tanpa harus membangun ulang sistem dari awal.

---

# 2. Latar Belakang

Website sekolah saat ini menggunakan WordPress dan akan digantikan dengan aplikasi baru.

Permasalahan utama yang ingin diselesaikan:

1. Tampilan website lama sudah tidak sesuai kebutuhan website sekolah modern.
2. Struktur konten perlu dibuat lebih sistematis.
3. Website harus lebih mudah dipelihara.
4. Pengelolaan berita dan informasi harus dapat dilakukan melalui sistem admin.
5. Sekolah memiliki banyak aplikasi pada subdomain yang saat ini belum terintegrasi secara navigasi dalam satu portal utama.
6. Foto dan media website berpotensi memenuhi storage hosting apabila seluruh file disimpan secara lokal.
7. Website harus memiliki struktur yang memungkinkan pengembangan jangka panjang.
8. Teknologi harus sesuai dengan environment Hostinger dan sebisa mungkin konsisten dengan website lain yang telah dikelola sekolah.

---

# 3. Tujuan Produk

## 3.1 Tujuan Utama

Membangun website utama SMA Negeri 1 Babat yang menjadi pusat:

- informasi resmi sekolah;
- berita dan publikasi;
- pengumuman;
- agenda;
- profil sekolah;
- informasi guru dan tenaga kependidikan;
- prestasi;
- dokumentasi kegiatan;
- dokumen publik;
- akses seluruh layanan digital sekolah.

## 3.2 Tujuan Operasional

Website harus memungkinkan admin:

- memperbarui berita tanpa mengubah source code;
- membuat pengumuman;
- mengelola agenda;
- mengubah informasi profil sekolah;
- mengelola guru dan tenaga kependidikan;
- mengelola galeri;
- mengelola prestasi;
- mengelola dokumen publik;
- menambah atau mengubah link subdomain;
- mengubah banner dan konten homepage;
- mengelola informasi global website.

---

# 4. Prinsip Pengembangan

Sistem harus mengikuti prinsip berikut.

### 4.1 Maintainable

Kode harus modular, mudah dibaca, dan mengikuti konvensi Laravel.

### 4.2 Hostinger-Compatible

Tidak menggunakan arsitektur yang menyulitkan deployment pada environment Hostinger.

### 4.3 CMS-Driven

Konten utama tidak boleh hardcoded jika secara operasional perlu diperbarui oleh admin.

### 4.4 Mobile-First

Website harus nyaman digunakan dari smartphone.

### 4.5 Modular

Berita, agenda, galeri, layanan digital, profil, dan modul lainnya harus terpisah secara domain logic.

### 4.6 Secure by Default

Sistem harus menerapkan validasi, autentikasi, authorization, proteksi upload, dan praktik keamanan Laravel.

### 4.7 Performance-Oriented

Website tidak boleh bergantung pada asset besar yang tidak teroptimasi.

### 4.8 Future-Proof

Arsitektur harus memungkinkan penambahan fitur tanpa restrukturisasi besar.

---

# 5. Technology Baseline

Requirement teknis yang dikunci untuk proyek:

| Komponen | Requirement |
|---|---|
| Backend Framework | Laravel 11 |
| PHP | 8.2 atau lebih tinggi |
| Production Hosting | Hostinger |
| Database | MySQL |
| Frontend Rendering | Server-side Laravel |
| Responsive UI | Wajib |
| Environment Configuration | `.env` |
| Version Control | Git |

## 5.1 Frontend

Frontend diutamakan menggunakan pendekatan yang kompatibel dan sederhana untuk Laravel.

Rekomendasi:

- Laravel Blade;
- Tailwind CSS;
- JavaScript minimal sesuai kebutuhan;
- Alpine.js jika dibutuhkan untuk interaksi ringan.

SPA framework seperti React/Vue tidak menjadi requirement V1.

## 5.2 Admin Panel

Website wajib memiliki CMS/admin panel.

Implementasinya dapat menggunakan Filament selama kompatibel dengan Laravel 11 dan kebutuhan deployment.

Admin tidak boleh diwajibkan mengubah file source code untuk memperbarui konten.

## 5.3 Media Storage

Media website harus mendukung penyimpanan eksternal/cloud.

Prioritas penggunaan:

- foto guru/karyawan;
- foto berita;
- galeri;
- banner;
- gambar prestasi;
- media besar lainnya.

Cloudinary dapat digunakan sebagai media provider.

Database hanya menyimpan URL/public identifier/metadata media sesuai kebutuhan.

---

# 6. Target Pengguna

## 6.1 Siswa

Kebutuhan utama:

- berita;
- pengumuman;
- agenda;
- CBT;
- absensi;
- BK;
- OSIS;
- kelulusan;
- layanan pembelajaran;
- layanan digital lainnya.

## 6.2 Orang Tua / Wali Murid

Kebutuhan:

- informasi resmi sekolah;
- pengumuman;
- berita;
- agenda;
- profil sekolah;
- prestasi;
- SPMB;
- kontak sekolah.

## 6.3 Guru

Kebutuhan:

- berita;
- agenda;
- pengumuman;
- layanan guru;
- piket;
- presensi;
- pembelajaran;
- dokumen publik.

## 6.4 Tenaga Kependidikan

Kebutuhan:

- informasi sekolah;
- layanan digital;
- agenda;
- pengumuman.

## 6.5 Calon Peserta Didik

Kebutuhan:

- profil sekolah;
- fasilitas;
- prestasi;
- kegiatan sekolah;
- SPMB;
- kontak.

## 6.6 Masyarakat Umum

Kebutuhan:

- profil resmi;
- berita;
- prestasi;
- galeri;
- kontak;
- informasi publik.

## 6.7 Admin Website

Kebutuhan:

- dashboard CMS;
- CRUD seluruh konten;
- workflow publikasi;
- manajemen media;
- pengaturan website.

---

# 7. Scope Website V1

Fitur publik yang masuk V1:

1. Beranda
2. Profil Sekolah
3. Guru dan Tenaga Kependidikan
4. Berita
5. Pengumuman
6. Agenda
7. Prestasi
8. Galeri
9. Dokumen
10. Layanan Digital
11. Kontak
12. Search dasar jika feasible

---

# 8. Sitemap V1

## 8.1 Navigasi Utama

- Beranda
- Profil
- Akademik
- Kesiswaan
- Berita
- Layanan Digital
- Galeri
- Kontak

## 8.2 Profil

- Sejarah Sekolah
- Visi dan Misi
- Sambutan Kepala Sekolah
- Struktur Organisasi
- Guru dan Tenaga Kependidikan
- Sarana dan Prasarana

## 8.3 Akademik

- Kurikulum
- Kalender Pendidikan
- Prestasi Akademik
- Dokumen Akademik

## 8.4 Kesiswaan

- OSIS
- Ekstrakurikuler
- Prestasi Siswa
- Bimbingan Konseling
- Tata Tertib

## 8.5 Berita

- Semua Berita
- Kategori Berita
- Detail Berita

## 8.6 Pengumuman

- Daftar Pengumuman
- Detail Pengumuman

## 8.7 Agenda

- Daftar Agenda
- Detail Agenda

## 8.8 Prestasi

- Prestasi Siswa
- Prestasi Guru
- Prestasi Sekolah

## 8.9 Galeri

- Daftar Album
- Detail Album

## 8.10 Dokumen

- Dokumen Publik
- Surat Edaran
- Kalender Pendidikan
- Unduhan

## 8.11 Layanan Digital

- Semua Layanan
- Filter berdasarkan kategori

## 8.12 Kontak

- Alamat
- Telepon
- Email
- Peta
- Media Sosial

---

# 9. Homepage

Homepage berfungsi sebagai **dashboard publik sekolah**, bukan sekadar halaman sambutan.

## 9.1 Susunan Homepage Desktop

1. Top Bar
2. Header / Navbar
3. Hero Section
4. Quick Access
5. Berita Terbaru
6. Pengumuman Penting
7. Layanan Digital
8. Profil Singkat Sekolah
9. Agenda Sekolah
10. Prestasi Terbaru
11. Galeri Kegiatan
12. Statistik Sekolah
13. Sambutan Kepala Sekolah
14. Footer

## 9.2 Prioritas Informasi

Prioritas homepage:

1. Pengumuman penting
2. Layanan Digital
3. Berita terbaru
4. Agenda
5. Prestasi
6. Informasi profil

## 9.3 Hero

Hero harus memuat:

- identitas SMA Negeri 1 Babat;
- foto sekolah/kegiatan;
- tagline;
- CTA Profil;
- CTA Layanan Digital.

Contoh:

**Selamat Datang di Website Resmi SMA Negeri 1 Babat**

> Portal informasi, publikasi, dan layanan digital SMA Negeri 1 Babat.

CTA:

- Lihat Profil Sekolah
- Layanan Digital

---

# 10. Modul Berita

## 10.1 Fungsi

Admin dapat membuat dan mempublikasikan berita kegiatan sekolah.

## 10.2 Data

Berita minimal memiliki:

- judul;
- slug;
- ringkasan;
- isi;
- featured image;
- kategori;
- penulis;
- status;
- tanggal publikasi;
- SEO title;
- SEO description.

## 10.3 Status

Minimal:

- Draft
- Published
- Archived

## 10.4 Kategori Awal

- Berita Sekolah
- Akademik
- Kesiswaan
- Prestasi
- Ekstrakurikuler
- Humas
- SPMB

## 10.5 Requirement Publik

Pengunjung dapat:

- melihat daftar berita;
- membuka detail;
- melihat kategori;
- melakukan pagination;
- melihat berita terbaru;
- melihat berita terkait.

---

# 11. Modul Pengumuman

Pengumuman memiliki:

- judul;
- slug;
- isi;
- tanggal mulai;
- tanggal berakhir;
- lampiran;
- status;
- penanda penting.

Pengumuman penting dapat ditampilkan secara khusus pada homepage.

Pengumuman yang sudah kedaluwarsa dapat tetap disimpan sebagai arsip.

---

# 12. Modul Agenda

Agenda memiliki:

- judul;
- slug;
- deskripsi;
- tanggal mulai;
- tanggal selesai;
- waktu;
- lokasi;
- kategori;
- penanggung jawab;
- status.

Homepage menampilkan agenda terdekat.

---

# 13. Modul Guru dan Tenaga Kependidikan

## 13.1 Informasi Publik

Data yang dapat ditampilkan:

- foto;
- nama;
- gelar;
- jabatan;
- mata pelajaran/unit kerja;
- kategori;
- status aktif.

## 13.2 Kategori

Contoh:

- Pimpinan
- Guru
- Tenaga Administrasi
- Laboran
- Pustakawan
- BK
- Karyawan

## 13.3 Privasi

Jangan menampilkan secara publik:

- NIK;
- alamat rumah;
- nomor telepon pribadi;
- dokumen pegawai;
- informasi pribadi sensitif lainnya.

---

# 14. Modul Prestasi

Prestasi dapat berasal dari:

- siswa;
- guru;
- sekolah.

Data minimal:

- judul;
- deskripsi;
- kategori;
- tingkat;
- peserta;
- pembimbing;
- tanggal;
- tahun;
- foto;
- status.

Tingkat:

- Sekolah
- Kecamatan
- Kabupaten
- Provinsi
- Nasional
- Internasional

---

# 15. Modul Galeri

Galeri berbasis album.

Album memiliki:

- judul;
- slug;
- deskripsi;
- kategori;
- tanggal kegiatan;
- cover;
- status.

Album memiliki banyak foto.

Foto memiliki:

- file/media;
- caption;
- urutan.

Media dioptimalkan melalui cloud media apabila digunakan.

---

# 16. Modul Dokumen

Dokumen publik memiliki:

- judul;
- kategori;
- deskripsi;
- tahun;
- file;
- status.

Fitur publik:

- pencarian;
- filter kategori;
- filter tahun;
- download.

---

# 17. Modul Layanan Digital

Layanan Digital merupakan salah satu fitur inti website.

Tujuan:

> Menjadikan website utama sebagai gateway resmi ke seluruh sistem dan aplikasi sekolah.

## 17.1 Data Layanan

Setiap layanan memiliki:

- nama;
- slug;
- deskripsi singkat;
- URL;
- ikon;
- kategori;
- status;
- jenis akses;
- urutan;
- flag tampil di homepage.

## 17.2 Status

- Aktif
- Maintenance
- Musiman
- Pengembangan
- Arsip

## 17.3 Jenis Akses

- Publik
- Internal
- Publik Terbatas
- Khusus Guru
- Khusus Siswa
- Khusus Admin

## 17.4 Layanan yang Telah Diidentifikasi

| Layanan | Subdomain |
|---|---|
| Absensi Siswa | `absen.smanegeri1babatlmg.sch.id` |
| Absensi Kesiswaan | `absn.smanegeri1babatlmg.sch.id` |
| Voting Stand Bazar | `bazar.smanegeri1babatlmg.sch.id` |
| Ruang Belajar PAI | `belajarpai.smanegeri1babatlmg.sch.id` |
| Bimbingan Konseling | `bk.smanegeri1babatlmg.sch.id` |
| CBT / Ujian Online | `exam.smanegeri1babatlmg.sch.id` |
| Ruang Belajar Informatika | `informatika.smanegeri1babatlmg.sch.id` |
| Cek Kelulusan | `kelulusan.smanegeri1babatlmg.sch.id` |
| Koreksi Tugas AI | `koreksi.smanegeri1babatlmg.sch.id` |
| Buku Tamu Digital | `lobby.smanegeri1babatlmg.sch.id` |
| Ruang Belajar Matematika | `matematika.smanegeri1babatlmg.sch.id` |
| OSIS | `osis.smanegeri1babatlmg.sch.id` |
| Piket Guru | `piketguru.smanegeri1babatlmg.sch.id` |
| Laboratorium Sekolah | `lab.smanegeri1babatlmg.sch.id` |
| Presensi Harian & Acara | `presensi.smanegeri1babatlmg.sch.id` |

SPMB dapat ditambahkan sebagai layanan apabila tetap menggunakan subdomain khusus.

## 17.5 Kategori Layanan

- Layanan Siswa
- Layanan Guru & Tendik
- Layanan Akademik
- Layanan Pembelajaran
- Layanan Kesiswaan
- Layanan Laboratorium
- Layanan Event
- Layanan Front Office

---

# 18. Modul Halaman Statis

Admin harus dapat mengelola halaman seperti:

- Sejarah Sekolah
- Visi dan Misi
- Sambutan Kepala Sekolah
- Struktur Organisasi
- Sarana Prasarana
- Kurikulum
- Tata Tertib

Minimal field:

- judul;
- slug;
- isi;
- featured image;
- status;
- SEO title;
- SEO description.

---

# 19. Modul Banner

Admin dapat:

- menambahkan banner;
- mengubah banner;
- menonaktifkan banner;
- menentukan urutan;
- menambahkan CTA.

Field:

- judul;
- subjudul;
- gambar;
- teks tombol;
- URL tombol;
- status;
- urutan.

---

# 20. Statistik Sekolah

Homepage dapat menampilkan statistik seperti:

- jumlah siswa;
- jumlah guru dan tendik;
- jumlah rombel;
- jumlah ekstrakurikuler;
- jumlah prestasi.

Semua statistik harus dapat diedit melalui admin.

Angka tidak boleh di-hardcode di template.

---

# 21. Site Settings

Administrator dapat mengubah:

- nama sekolah;
- logo;
- favicon;
- alamat;
- telepon;
- email;
- Google Maps;
- Instagram;
- YouTube;
- Facebook;
- TikTok;
- jam pelayanan;
- footer;
- informasi global lain.

---

# 22. Admin Panel

Admin panel menjadi pusat pengelolaan website.

## 22.1 Modul Admin V1

- Dashboard
- Berita
- Kategori Berita
- Pengumuman
- Agenda
- Guru & Tendik
- Prestasi
- Galeri
- Dokumen
- Layanan Digital
- Halaman Statis
- Banner
- Statistik Sekolah
- Site Settings
- User Management

## 22.2 Dashboard

Minimal menampilkan:

- jumlah berita;
- jumlah draft;
- pengumuman aktif;
- agenda terdekat;
- jumlah GTK;
- jumlah layanan;
- jumlah dokumen;
- konten terakhir diperbarui.

---

# 23. Role dan Authorization

Minimal role V1:

### Super Admin

Hak penuh terhadap sistem.

### Admin Website

Mengelola hampir seluruh konten website.

### Editor

Mengelola/review berita dan publikasi.

### Kontributor

Membuat konten draft tanpa kewenangan administratif penuh.

Detail permission dapat disederhanakan pada awal build selama arsitektur mendukung penambahan permission selanjutnya.

---

# 24. Search

Search global bukan mandatory pada tahap awal apabila menambah kompleksitas berlebihan.

Namun modul berikut sebaiknya memiliki search internal:

- berita;
- GTK;
- dokumen;
- layanan digital.

Search harus menggunakan database query dan tidak membutuhkan layanan eksternal pada V1.

---

# 25. UI/UX Requirement

## 25.1 Gaya

Desain harus:

- profesional;
- modern;
- clean;
- resmi;
- tidak terlihat seperti template blog;
- memiliki whitespace yang cukup;
- konsisten;
- mudah dipahami.

## 25.2 Warna

Arah awal:

- biru sebagai primary;
- putih sebagai background utama;
- gold/kuning sebagai accent terbatas;
- warna netral untuk teks dan border.

Final brand color dapat disesuaikan dengan identitas resmi sekolah.

## 25.3 Responsive

Wajib mendukung:

- smartphone;
- tablet;
- desktop.

Mobile bukan versi yang diperkecil dari desktop, tetapi harus memiliki hierarchy yang sesuai kebutuhan pengguna mobile.

---

# 26. Accessibility

Minimal:

- contrast teks memadai;
- ukuran font nyaman dibaca;
- link memiliki state yang jelas;
- tombol memiliki target sentuh yang cukup;
- gambar informatif memiliki alt text;
- form memiliki label;
- heading hierarchy benar;
- tidak mengandalkan warna saja untuk menyampaikan status.

Target praktik: WCAG 2.1 AA sejauh layak untuk V1.

---

# 27. SEO

Setiap halaman konten harus mendukung:

- title;
- meta description;
- canonical URL;
- Open Graph dasar;
- slug yang bersih.

Berita harus memiliki URL seperti:

`/berita/judul-berita`

Hindari URL berbasis ID sebagai URL utama publik.

Website juga harus memiliki:

- sitemap XML;
- robots.txt;
- favicon;
- structured metadata dasar bila relevan.

---

# 28. Performance

Target:

- homepage ringan;
- gambar responsive;
- lazy loading pada gambar non-prioritas;
- asset production ter-minify;
- database query efisien;
- pagination untuk koleksi besar;
- eager loading untuk mencegah N+1 query;
- cache digunakan jika memberikan manfaat nyata.

Target pengalaman:

- halaman utama terasa cepat pada koneksi mobile normal;
- tidak ada hero image berukuran sangat besar tanpa optimasi.

---

# 29. Media Handling

Untuk setiap upload media:

- validasi MIME type;
- validasi ukuran;
- rename/identifier aman;
- alt text/caption jika diperlukan;
- hindari original image sangat besar di frontend.

Foto GTK idealnya memiliki rasio konsisten.

Foto berita sebaiknya memiliki aspect ratio seragam untuk card.

---

# 30. Security Requirements

Minimal wajib:

- CSRF protection Laravel;
- authentication admin;
- authorization;
- server-side validation;
- secure password hashing;
- rate limiting bila dibutuhkan;
- tidak menyimpan secret dalam Git;
- `.env` tidak dipublikasikan;
- `APP_DEBUG=false` pada production;
- validasi file upload;
- proteksi XSS pada output konten;
- tidak membuka informasi error detail di production;
- dependency rutin diperbarui.

---

# 31. Database Requirement

Database menggunakan MySQL.

Entitas awal:

- users
- news_categories
- news
- announcements
- agendas
- teachers_staff
- achievements
- galleries
- gallery_photos
- documents
- digital_services
- pages
- banners
- school_statistics
- site_settings

Relasi utama:

- category has many news
- user has many news
- gallery has many gallery photos

Struktur database final harus dibuat melalui Laravel migration.

Dilarang bergantung pada perubahan schema manual yang tidak tercatat dalam migration.

---

# 32. URL Structure

Contoh:

```text
/
 /berita
 /berita/{slug}
 /berita/kategori/{slug}

 /pengumuman
 /pengumuman/{slug}

 /agenda
 /agenda/{slug}

 /prestasi
 /prestasi/{slug}

 /galeri
 /galeri/{slug}

 /dokumen

 /layanan-digital

 /profil/sejarah
 /profil/visi-misi
 /profil/sambutan-kepala-sekolah
 /profil/struktur-organisasi
 /profil/guru-karyawan
 /profil/sarana-prasarana

 /kontak

 /admin
```

---

# 33. Migration dari Website Lama

Website baru akan menggantikan website WordPress lama.

Sebelum cutover:

1. Backup penuh WordPress.
2. Backup database.
3. Backup uploads/media.
4. Identifikasi konten yang masih relevan.
5. Migrasikan konten terpilih.
6. Jangan otomatis memigrasikan seluruh data lama.
7. Pastikan URL penting yang berubah menggunakan redirect jika diperlukan.
8. Validasi kembali data profil sekolah sebelum publish.

Prioritas migrasi:

- sejarah;
- visi/misi;
- informasi sekolah;
- berita penting;
- data GTK terbaru;
- dokumen aktif;
- kontak;
- informasi kepala sekolah;
- struktur organisasi.

---

# 34. Deployment

Deployment target adalah Hostinger.

Requirement deployment:

- PHP 8.2+;
- extension Laravel tersedia;
- database MySQL;
- `.env` production;
- document root diarahkan dengan benar;
- `APP_ENV=production`;
- `APP_DEBUG=false`;
- HTTPS aktif;
- Laravel cache optimization digunakan;
- cron/scheduler dikonfigurasi jika dibutuhkan;
- backup tersedia sebelum deployment.

Website baru tidak boleh menggantikan production lama sebelum testing selesai.

---

# 35. Environment

Minimal environment:

### Local

Untuk development di VS Code.

### Production

Hostinger + domain utama.

Jika memungkinkan dapat ditambahkan staging environment sebelum production.

Configuration harus berbasis `.env`, bukan hardcoded.

---

# 36. Logging dan Error Handling

Sistem harus:

- menggunakan Laravel logging;
- tidak menampilkan stack trace pada production;
- mencatat exception penting;
- menyediakan halaman error 404/500 yang sesuai branding.

---

# 37. Backup

Backup minimal mencakup:

- database;
- konfigurasi penting;
- media penting jika provider eksternal membutuhkan backup terpisah.

Prosedur backup dan restore wajib didokumentasikan sebelum production release.

---

# 38. Analytics

Analytics dapat ditambahkan pada V1 apabila diperlukan.

Jika digunakan, integrasi harus:

- tidak menghambat performa;
- tidak membuat UI bergantung pada analytics;
- mengikuti kebijakan privasi yang sesuai.

Dashboard analytics kompleks bukan requirement V1.

---

# 39. Non-Goals V1

Fitur berikut tidak menjadi target awal:

- Single Sign-On antar semua subdomain;
- integrasi database semua subdomain;
- aplikasi Android/iOS;
- forum siswa;
- chat internal;
- sistem akademik/SIAKAD penuh;
- LMS baru;
- email server baru;
- AI generator konten;
- dashboard BI kompleks;
- monitoring semua subdomain secara real-time.

Fitur tersebut dapat dievaluasi pada fase berikutnya.

---

# 40. Acceptance Criteria Utama

Website V1 dianggap berhasil apabila:

### Functional

- Homepage dapat diakses.
- Admin dapat login.
- Admin dapat mengelola berita.
- Admin dapat mengelola kategori.
- Admin dapat mengelola pengumuman.
- Admin dapat mengelola agenda.
- Admin dapat mengelola GTK.
- Admin dapat mengelola prestasi.
- Admin dapat mengelola galeri.
- Admin dapat mengelola dokumen.
- Admin dapat mengelola layanan digital.
- Admin dapat mengubah banner.
- Admin dapat mengubah halaman statis.
- Admin dapat mengubah informasi global website.

### Public Website

- Berita hanya tampil jika published.
- Pengumuman aktif tampil dengan benar.
- Agenda dapat ditampilkan berdasarkan tanggal.
- Layanan Digital membuka subdomain yang benar.
- Foto GTK tampil konsisten.
- Galeri dapat dibuka.
- Dokumen dapat diunduh.
- Kontak sekolah tampil benar.

### Responsive

- Tidak ada horizontal overflow pada mobile.
- Navbar dapat digunakan di mobile.
- Card responsive.
- Teks tidak terpotong.
- Gambar tidak merusak layout.

### Performance

- Homepage tidak melakukan query berlebihan.
- Gambar telah dioptimalkan.
- Collection besar menggunakan pagination.

### Security

- Admin tidak dapat diakses tanpa autentikasi.
- `.env` tidak terekspos.
- Upload tervalidasi.
- Data sensitif GTK tidak tampil publik.
- Production menggunakan `APP_DEBUG=false`.

### Deployment

- Website berjalan di Hostinger.
- HTTPS aktif.
- Database production terhubung.
- Media eksternal berfungsi.
- Backup website lama tersedia.

---

# 41. Definition of Done

Sebuah fitur dianggap selesai jika:

1. Requirement fitur terpenuhi.
2. UI desktop selesai.
3. UI mobile selesai.
4. Validasi input tersedia.
5. Authorization diperiksa.
6. Empty state tersedia.
7. Error state tidak merusak aplikasi.
8. Database migration tersedia.
9. Tidak ada credential hardcoded.
10. Testing dasar dilakukan.
11. Tidak menimbulkan regression pada fitur lain.
12. Dokumentasi diperbarui jika diperlukan.

---

# 42. Development Rules

Selama build:

- Jangan membuat semua modul sekaligus.
- Selesaikan satu domain fitur secara utuh sebelum berpindah terlalu jauh.
- Migration menjadi source of truth database.
- Jangan mengubah database production manual.
- Jangan memasukkan credential ke Git.
- Gunakan naming convention Laravel.
- Hindari dependency jika Laravel native sudah cukup.
- Jangan over-engineer V1.
- Gunakan reusable component untuk UI berulang.
- Hindari business logic kompleks langsung di Blade.
- Query database harus berada pada layer yang tepat.
- Gunakan validation request/form sesuai kebutuhan.
- Semua fitur harus tetap kompatibel dengan deployment Hostinger.

---

# 43. Prioritas V1

## P0 — Wajib

- Laravel foundation
- Database
- Authentication admin
- Homepage
- Berita
- Pengumuman
- Layanan Digital
- Profil
- GTK
- Site Settings
- Responsive UI
- Deployment Hostinger

## P1 — Penting

- Agenda
- Prestasi
- Galeri
- Dokumen
- Banner
- Statistik
- SEO

## P2 — Enhancement

- Advanced search
- Analytics
- Social share
- Scheduled publishing
- Advanced roles
- Additional caching
- Advanced dashboard

---

# 44. Product Vision

Website ini tidak dibangun hanya untuk mengganti WordPress.

Target jangka panjangnya adalah membangun fondasi **ekosistem digital SMA Negeri 1 Babat**.

Website utama menjadi:

> **Satu pintu informasi resmi sekolah dan gateway menuju seluruh layanan digital SMA Negeri 1 Babat.**

Arsitektur V1 harus cukup sederhana untuk dipelihara saat ini, tetapi cukup terstruktur agar kebutuhan baru dapat ditambahkan tanpa membangun ulang dari awal.