# DATABASE DESIGN — Website Resmi SMA Negeri 1 Babat

> **Versi:** 1.0
> **Source of truth produk:** [docs/PRD.md](file:///c:/laragon/www/web-smaba/docs/PRD.md)
> **Arsitektur:** [docs/ARCHITECTURE.md](file:///c:/laragon/www/web-smaba/docs/ARCHITECTURE.md)
> **Engine:** MySQL 8.x
> **ORM:** Laravel Eloquent
> **Schema management:** Laravel Migrations (satu-satunya source of truth schema)

---

## 1. Entity Relationship Diagram

```
users ──────────────< news >────────────── news_categories
                       │
                       │ (author_id FK)
                       │

galleries ──────────< gallery_photos

announcements          (standalone)
agendas                (standalone)
teachers_staff         (standalone)
achievements           (standalone)
documents              (standalone)
digital_services       (standalone)
pages                  (standalone)
banners                (standalone)
school_statistics      (standalone)
site_settings          (standalone)
```

Relasi utama:
- `news_categories` 1 → N `news`
- `users` 1 → N `news`
- `galleries` 1 → N `gallery_photos`

---

## 2. Konvensi

| Konvensi | Penerapan |
|---|---|
| Primary key | `id` (unsigned bigint, auto-increment) |
| Foreign key | `{entity}_id` (unsigned bigint) |
| Timestamps | `created_at`, `updated_at` (otomatis oleh Laravel) |
| Soft delete | `deleted_at` (timestamp nullable) — hanya pada entitas yang eksplisit membutuhkan |
| Slug | `VARCHAR(255)`, unique index |
| Status | `VARCHAR(20)` — menggunakan PHP enum di model |
| Media path | `VARCHAR(500)` — menyimpan relative path / cloud identifier |
| Rich text | `LONGTEXT` — untuk konten HTML dari rich text editor |
| Ordering | `sort_order` (unsigned integer, default 0) |
| Boolean flag | `BOOLEAN` — tinyint(1) di MySQL |

---

## 3. Entity Details

### 3.0 Category Strategy (V1 Simplification)

Sebagai penyederhanaan yang disengaja pada V1 untuk menjaga arsitektur tetap mudah dipelihara dan tidak over-engineered:
- Hanya **Berita** yang memiliki tabel kategori terpisah (`news_categories`).
- Entitas lain (`agendas`, `teachers_staff`, `achievements`, `galleries`, `documents`, `digital_services`) menggunakan kolom string biasa (`VARCHAR`) untuk `category`.
- Tidak ada pembuatan tabel kategori tambahan untuk entitas-entitas tersebut kecuali ada requirement baru di masa depan.

---

### 3.1 `users`

**Purpose:** Admin panel users (Filament). Bukan pengguna publik.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `name` | VARCHAR(255) | NO | | | Nama lengkap |
| `email` | VARCHAR(255) | NO | | UNIQUE | Login email |
| `email_verified_at` | TIMESTAMP | YES | NULL | | Laravel default |
| `password` | VARCHAR(255) | NO | | | Hashed |
| `remember_token` | VARCHAR(100) | YES | NULL | | Laravel default |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:** `email` (unique)

**Soft Delete:** Tidak. User dihapus = dihapus permanen. Jumlah user admin sedikit.

**Catatan:** Role dan permission dikelola oleh Filament Shield / Spatie Permission (tabel terpisah yang di-generate otomatis oleh package).

---

### 3.2 `news_categories`

**Purpose:** Kategori berita. Satu kategori dapat memiliki banyak berita.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `name` | VARCHAR(255) | NO | | | Nama kategori |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL-friendly |
| `description` | TEXT | YES | NULL | | Deskripsi opsional |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan tampil |
| `is_active` | BOOLEAN | NO | true | | Aktif/tidak |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:** `slug` (unique), `is_active`

**Soft Delete:** Tidak. Kategori yang tidak dipakai cukup di-nonaktifkan via `is_active`.

**Kategori awal (seeder):**
- Berita Sekolah, Akademik, Kesiswaan, Prestasi, Ekstrakurikuler, Humas, SPMB

---

### 3.3 `news`

**Purpose:** Artikel berita kegiatan sekolah. Modul konten utama website.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `category_id` | BIGINT UNSIGNED | NO | | FK → news_categories.id | Kategori berita |
| `author_id` | BIGINT UNSIGNED | YES | NULL | FK → users.id | Penulis (admin user) |
| `title` | VARCHAR(255) | NO | | | Judul berita |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL: /berita/{slug} |
| `excerpt` | TEXT | YES | NULL | | Ringkasan singkat |
| `body` | LONGTEXT | NO | | | Isi berita (HTML dari rich text editor) |
| `featured_image` | VARCHAR(500) | YES | NULL | | Path/URL gambar utama |
| `status` | VARCHAR(20) | NO | 'draft' | | draft / published / archived |
| `published_at` | TIMESTAMP | YES | NULL | INDEX | Tanggal publikasi |
| `seo_title` | VARCHAR(255) | YES | NULL | | Override meta title |
| `seo_description` | VARCHAR(500) | YES | NULL | | Override meta description |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |
| `deleted_at` | TIMESTAMP | YES | NULL | | Soft delete |

**Indexes:**
- `slug` (unique)
- `status` + `published_at` (composite — untuk query berita published terbaru)
- `category_id` (FK index)
- `author_id` (FK index)

**Foreign Keys:**
- `category_id` → `news_categories.id` (ON DELETE RESTRICT)
- `author_id` → `users.id` (ON DELETE SET NULL — agar berita tetap tersedia ketika akun penulis dihapus)

**Soft Delete:** Ya. Berita yang dihapus bisa di-restore.

---

### 3.4 `announcements`

**Purpose:** Pengumuman resmi sekolah. Mendukung tanggal kedaluwarsa dan flag penting.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul pengumuman |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL: /pengumuman/{slug} |
| `body` | LONGTEXT | NO | | | Isi pengumuman (HTML) |
| `start_date` | DATE | NO | | | Tanggal mulai berlaku |
| `end_date` | DATE | YES | NULL | | Tanggal berakhir (null = tidak ada batas) |
| `attachment` | VARCHAR(500) | YES | NULL | | Path/URL file lampiran |
| `status` | VARCHAR(20) | NO | 'draft' | | draft / published / archived |
| `is_important` | BOOLEAN | NO | false | | Tampilkan sebagai pengumuman penting |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |
| `deleted_at` | TIMESTAMP | YES | NULL | | Soft delete |

**Indexes:**
- `slug` (unique)
- `status` + `start_date` (composite)
- `is_important`

**Soft Delete:** Ya.

---

### 3.5 `agendas`

**Purpose:** Agenda kegiatan sekolah. Ditampilkan di homepage dan halaman agenda.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul kegiatan |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL: /agenda/{slug} |
| `description` | LONGTEXT | YES | NULL | | Deskripsi kegiatan (HTML) |
| `start_date` | DATE | NO | | | Tanggal mulai |
| `end_date` | DATE | YES | NULL | | Tanggal selesai (null = satu hari) |
| `start_time` | TIME | YES | NULL | | Waktu mulai |
| `end_time` | TIME | YES | NULL | | Waktu selesai |
| `location` | VARCHAR(255) | YES | NULL | | Lokasi kegiatan |
| `category` | VARCHAR(100) | YES | NULL | | Kategori agenda (string, bukan FK) |
| `person_in_charge` | VARCHAR(255) | YES | NULL | | Penanggung jawab |
| `status` | VARCHAR(20) | NO | 'draft' | | draft / published / archived |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `slug` (unique)
- `status` + `start_date` (composite — untuk query agenda terdekat)

**Soft Delete:** Tidak. Agenda yang lewat bisa di-archive via status.

**Catatan:** `category` disimpan sebagai string biasa karena PRD tidak mendefinisikan tabel kategori agenda terpisah. Jika kebutuhan bertambah, bisa di-refactor menjadi FK ke tabel terpisah.

---

### 3.6 `teachers_staff`

**Purpose:** Data guru dan tenaga kependidikan yang ditampilkan secara publik.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `name` | VARCHAR(255) | NO | | | Nama lengkap tanpa gelar |
| `front_title` | VARCHAR(50) | YES | NULL | | Gelar depan (Dr., Drs., dll) |
| `back_title` | VARCHAR(50) | YES | NULL | | Gelar belakang (S.Pd., M.Pd., dll) |
| `nip` | VARCHAR(30) | YES | NULL | UNIQUE | NIP (nullable: honorer tidak punya NIP) |
| `position` | VARCHAR(255) | YES | NULL | | Jabatan (Kepala Sekolah, Waka, dll) |
| `subject` | VARCHAR(255) | YES | NULL | | Mata pelajaran / unit kerja |
| `category` | VARCHAR(50) | NO | | | Pimpinan / Guru / TU / Laboran / dll |
| `photo` | VARCHAR(500) | YES | NULL | | Path/URL foto |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan tampil dalam kategori |
| `status` | VARCHAR(20) | NO | 'active' | | active / inactive |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `nip` (unique, nullable)
- `category` + `sort_order` (composite — untuk query per kategori berurut)
- `status`

**Soft Delete:** Tidak. GTK yang pensiun/pindah cukup di-set `is_active = false`.

**Privasi (PRD §13.3):** Tabel ini **tidak menyimpan** NIK, alamat rumah, nomor telepon pribadi, atau data sensitif lainnya. NIP disimpan sebagai identifier internal admin, **tidak ditampilkan di publik**.

---

### 3.7 `achievements`

**Purpose:** Prestasi siswa, guru, dan sekolah.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul/nama prestasi |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL: /prestasi/{slug} |
| `description` | LONGTEXT | YES | NULL | | Deskripsi (HTML) |
| `category` | VARCHAR(50) | NO | | | siswa / guru / sekolah |
| `level` | VARCHAR(50) | NO | | | sekolah / kecamatan / kabupaten / provinsi / nasional / internasional |
| `participant` | VARCHAR(255) | YES | NULL | | Nama peserta/tim |
| `supervisor` | VARCHAR(255) | YES | NULL | | Nama pembimbing |
| `event_name` | VARCHAR(255) | YES | NULL | | Nama event/kompetisi |
| `event_date` | DATE | YES | NULL | | Tanggal event |
| `year` | YEAR | NO | | | Tahun prestasi |
| `photo` | VARCHAR(500) | YES | NULL | | Path/URL foto |
| `status` | VARCHAR(20) | NO | 'draft' | | draft / published |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `slug` (unique)
- `category` + `year` (composite — filter per kategori dan tahun)
- `level`
- `status`

**Soft Delete:** Tidak. Prestasi jarang dihapus.

---

### 3.8 `galleries`

**Purpose:** Album foto kegiatan sekolah. Satu album memiliki banyak foto.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul album |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL: /galeri/{slug} |
| `description` | TEXT | YES | NULL | | Deskripsi album |
| `category` | VARCHAR(100) | YES | NULL | | Kategori (Akademik, Kesiswaan, dll) |
| `event_date` | DATE | YES | NULL | | Tanggal kegiatan |
| `cover_image` | VARCHAR(500) | YES | NULL | | Path/URL cover album |
| `status` | VARCHAR(20) | NO | 'draft' | | draft / published |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `slug` (unique)
- `status` + `event_date` (composite)

**Soft Delete:** Tidak.

---

### 3.9 `gallery_photos`

**Purpose:** Foto-foto dalam album galeri.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `gallery_id` | BIGINT UNSIGNED | NO | | FK → galleries.id | Album pemilik |
| `image` | VARCHAR(500) | NO | | | Path/URL file foto |
| `caption` | VARCHAR(500) | YES | NULL | | Keterangan foto |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan dalam album |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `gallery_id` + `sort_order` (composite)

**Foreign Keys:**
- `gallery_id` → `galleries.id` (CASCADE on delete — hapus album = hapus semua foto)

**Soft Delete:** Tidak.

---

### 3.10 `documents`

**Purpose:** Dokumen publik sekolah yang dapat diunduh (SK, edaran, kalender, dll).

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul dokumen |
| `category` | VARCHAR(100) | NO | | | Kategori (SK, Surat Edaran, Kalender, dll) |
| `description` | TEXT | YES | NULL | | Deskripsi singkat |
| `year` | YEAR | YES | NULL | | Tahun dokumen |
| `file_path` | VARCHAR(500) | NO | | | Path/URL file |
| `file_size` | INT UNSIGNED | YES | NULL | | Ukuran file dalam bytes |
| `file_type` | VARCHAR(50) | YES | NULL | | MIME type (pdf, docx, dll) |
| `status` | VARCHAR(20) | NO | 'published' | | draft / published |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `category` + `year` (composite — filter per kategori dan tahun)
- `status`

**Soft Delete:** Tidak.

---

### 3.11 `digital_services`

**Purpose:** Daftar layanan digital / aplikasi subdomain sekolah. Fitur inti website sebagai gateway.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `name` | VARCHAR(255) | NO | | | Nama layanan |
| `slug` | VARCHAR(255) | NO | | UNIQUE | Identifier URL-friendly |
| `description` | TEXT | YES | NULL | | Deskripsi singkat |
| `url` | VARCHAR(500) | NO | | | URL lengkap subdomain |
| `icon` | VARCHAR(255) | YES | NULL | | Nama ikon atau path gambar ikon |
| `category` | VARCHAR(100) | YES | NULL | | Layanan Siswa / Guru / Akademik / dll |
| `status` | VARCHAR(20) | NO | 'active' | | active / maintenance / seasonal / development / archived |
| `access_type` | VARCHAR(50) | NO | 'public' | | public / internal / restricted / teacher / student / admin |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan tampil |
| `show_on_homepage` | BOOLEAN | NO | false | | Tampilkan di homepage |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `slug` (unique)
- `status`
- `show_on_homepage`
- `category`

**Soft Delete:** Tidak. Layanan yang di-retired cukup di-set status `archived`.

---

### 3.12 `pages`

**Purpose:** Halaman statis CMS-driven (sejarah, visi-misi, sambutan, dll).

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul halaman |
| `slug` | VARCHAR(255) | NO | | UNIQUE | URL: /profil/{slug} |
| `body` | LONGTEXT | NO | | | Isi halaman (HTML dari rich text editor) |
| `featured_image` | VARCHAR(500) | YES | NULL | | Path/URL gambar header |
| `status` | VARCHAR(20) | NO | 'draft' | | draft / published |
| `seo_title` | VARCHAR(255) | YES | NULL | | Override meta title |
| `seo_description` | VARCHAR(500) | YES | NULL | | Override meta description |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan di navigasi |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `slug` (unique)
- `status`

**Soft Delete:** Tidak. Halaman sedikit dan jarang dihapus.

---

### 3.13 `banners`

**Purpose:** Hero banner/slider pada homepage. CMS-managed.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `title` | VARCHAR(255) | NO | | | Judul banner |
| `subtitle` | VARCHAR(500) | YES | NULL | | Subjudul / deskripsi pendek |
| `image` | VARCHAR(500) | NO | | | Path/URL gambar banner |
| `button_text` | VARCHAR(100) | YES | NULL | | Teks tombol CTA |
| `button_url` | VARCHAR(500) | YES | NULL | | URL tujuan CTA |
| `status` | VARCHAR(20) | NO | 'active' | | active / inactive |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan slider |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `status` + `sort_order` (composite — query banner aktif berurut)

**Soft Delete:** Tidak.

---

### 3.14 `school_statistics`

**Purpose:** Angka statistik yang ditampilkan di homepage (jumlah siswa, guru, dll). Semua angka dikelola melalui admin, tidak boleh di-hardcode.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `label` | VARCHAR(255) | NO | | | Label statistik (Jumlah Siswa, dll) |
| `value` | VARCHAR(100) | NO | | | Nilai (string agar fleksibel: "1.200+", "A", dll) |
| `icon` | VARCHAR(255) | YES | NULL | | Nama ikon atau path gambar |
| `sort_order` | INT UNSIGNED | NO | 0 | | Urutan tampil |
| `status` | VARCHAR(20) | NO | 'active' | | active / inactive |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `status` + `sort_order` (composite)

**Soft Delete:** Tidak.

---

### 3.15 `site_settings`

**Purpose:** Pengaturan global website (nama, logo, kontak, sosial media, footer). Pola key-value.

| Field | Type | Nullable | Default | Constraint | Keterangan |
|---|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | AI | PK | |
| `key` | VARCHAR(255) | NO | | UNIQUE | Identifier setting |
| `value` | TEXT | YES | NULL | | Nilai setting |
| `type` | VARCHAR(50) | NO | 'text' | | text / textarea / image / boolean / json |
| `group` | VARCHAR(100) | NO | 'general' | | Grup untuk pengelompokan di admin |
| `created_at` | TIMESTAMP | YES | NULL | | |
| `updated_at` | TIMESTAMP | YES | NULL | | |

**Indexes:**
- `key` (unique)
- `group`

**Soft Delete:** Tidak.

**Setting keys awal (seeder):**

| Group | Key | Type |
|---|---|---|
| general | `school_name` | text |
| general | `school_logo` | image |
| general | `school_favicon` | image |
| general | `school_tagline` | text |
| contact | `address` | textarea |
| contact | `phone` | text |
| contact | `email` | text |
| contact | `google_maps_embed` | textarea |
| contact | `service_hours` | text |
| social | `instagram_url` | text |
| social | `youtube_url` | text |
| social | `facebook_url` | text |
| social | `tiktok_url` | text |
| seo | `default_seo_title` | text |
| seo | `default_seo_description` | textarea |
| appearance | `footer_text` | textarea |

---

## 4. Foreign Key Summary

| Source Table | Source Column | Target Table | Target Column | On Delete |
|---|---|---|---|---|
| `news` | `category_id` | `news_categories` | `id` | RESTRICT |
| `news` | `author_id` | `users` | `id` | SET NULL |
| `gallery_photos` | `gallery_id` | `galleries` | `id` | CASCADE |

**Catatan:**
- `RESTRICT` pada `news.category_id` → mencegah penghapusan kategori yang masih memiliki berita.
- `SET NULL` pada `news.author_id` → memastikan berita tetap ada meskipun penulis (user) dihapus.
- `CASCADE` pada `gallery_photos.gallery_id` → menghapus album otomatis menghapus semua fotonya.

---

## 5. Soft Delete Summary

| Tabel | Soft Delete | Alasan |
|---|---|---|
| `news` | ✅ Ya | Berita penting bisa di-restore jika salah hapus |
| `announcements` | ✅ Ya | Pengumuman mungkin perlu di-restore |
| Semua tabel lain | ❌ Tidak | Data jarang dihapus, atau cukup dinonaktifkan via `is_active`/`status` |

---

## 6. Index Strategy

### Prinsip

- Index pada kolom yang digunakan di `WHERE`, `ORDER BY`, dan `JOIN`.
- Composite index untuk query pattern yang sering: `status` + `published_at`, `category` + `year`.
- Unique index pada semua `slug` untuk route model binding.
- Tidak over-index — tambahkan index baru hanya jika ada query lambat yang terukur.

### Query Pattern Utama dan Index-nya

| Query Pattern | Tabel | Index |
|---|---|---|
| Berita published terbaru | `news` | `(status, published_at)` |
| Berita per kategori | `news` | `(category_id)` |
| Pengumuman aktif | `announcements` | `(status, start_date)` |
| Agenda terdekat | `agendas` | `(status, start_date)` |
| GTK per kategori | `teachers_staff` | `(status, category, sort_order)` |
| Prestasi per kategori/tahun | `achievements` | `(status, category, year)` |
| Galeri terbaru | `galleries` | `(status, event_date)` |
| Dokumen per kategori/tahun | `documents` | `(status, category, year)` |
| Layanan di homepage | `digital_services` | `(status, show_on_homepage)` |
| Banner aktif | `banners` | `(status, sort_order)` |
| Statistik aktif | `school_statistics` | `(status, sort_order)` |
| Setting by key | `site_settings` | `(key)` unique |

---

## 7. Migration Order

Migration harus dibuat dalam urutan dependency:

```
1. users                    (sudah ada dari Laravel default)
2. news_categories          (standalone)
3. news                     (depends on: users, news_categories)
4. announcements            (standalone)
5. agendas                  (standalone)
6. teachers_staff           (standalone)
7. achievements             (standalone)
8. galleries                (standalone)
9. gallery_photos           (depends on: galleries)
10. documents               (standalone)
11. digital_services        (standalone)
12. pages                   (standalone)
13. banners                 (standalone)
14. school_statistics       (standalone)
15. site_settings           (standalone)
```

---

## 8. Seeder Plan

| Seeder | Data |
|---|---|
| `UserSeeder` | Super Admin user awal |
| `NewsCategorySeeder` | 7 kategori awal (PRD §10.4) |
| `SiteSettingSeeder` | Setting keys awal dengan nilai default |
| `DigitalServiceSeeder` | 15 layanan subdomain yang sudah teridentifikasi (PRD §17.4) |
| `SchoolStatisticSeeder` | Statistik placeholder (jumlah siswa, guru, dll) |

Seeder hanya menyediakan data foundational. Konten aktual dimasukkan melalui admin panel.

---

## 9. Catatan Teknis

### 9.1 Charset dan Collation

Semua tabel menggunakan `utf8mb4` charset dengan `utf8mb4_unicode_ci` collation (sudah di-set saat pembuatan database).

### 9.2 Media Columns

Kolom media (`featured_image`, `photo`, `image`, `cover_image`, `attachment`, `file_path`) menyimpan relative path atau cloud identifier — **bukan URL lengkap**. URL final di-resolve melalui `Storage::url()` di model accessor.

### 9.3 Slug Generation

Slug di-generate otomatis dari `title`/`name` menggunakan `Str::slug()` di Filament form atau model observer. Harus dicek uniqueness sebelum save.

### 9.4 Rich Text Storage

Field `body` pada `news`, `announcements`, `pages` menyimpan HTML dari Filament rich text editor. Output di Blade harus menggunakan `{!! $model->body !!}` dengan sanitasi yang tepat, atau menggunakan Filament's built-in safe HTML rendering.

### 9.5 Timestamp Timezone

`published_at`, `start_date`, `end_date` menggunakan timezone server. Laravel `APP_TIMEZONE` harus di-set ke `Asia/Jakarta` di production.
