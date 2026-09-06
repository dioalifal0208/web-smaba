# ARCHITECTURE — Website Resmi SMA Negeri 1 Babat

> **Dokumen ini menjelaskan arsitektur teknis V1.**
> **Source of truth produk: [docs/PRD.md](file:///c:/laragon/www/web-smaba/docs/PRD.md)**

---

## 1. Arsitektur Umum

Website menggunakan arsitektur **monolith server-rendered** standar Laravel.

```
┌─────────────────────────────────────────────────────┐
│                    Browser                          │
└────────────┬──────────────────────┬─────────────────┘
             │                      │
    Public Routes              Admin Routes
     (Blade + TW)            (Filament /admin)
             │                      │
┌────────────▼──────────────────────▼─────────────────┐
│                  Laravel 11                         │
│                                                     │
│  ┌──────────┐  ┌──────────┐  ┌───────────────────┐  │
│  │Controllers│  │  Models  │  │ Filament Resources│  │
│  └────┬─────┘  └────┬─────┘  └────────┬──────────┘  │
│       │              │                 │             │
│  ┌────▼──────────────▼─────────────────▼──────────┐  │
│  │               Eloquent ORM                     │  │
│  └────────────────────┬───────────────────────────┘  │
│                       │                             │
│  ┌────────────────────▼───────────────────────────┐  │
│  │                  MySQL                         │  │
│  └────────────────────────────────────────────────┘  │
│                                                     │
│  ┌────────────────────────────────────────────────┐  │
│  │          Cloud Media (Cloudinary)              │  │
│  └────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────┘
```

Tidak ada API layer, microservice, atau SPA. Semua request menghasilkan HTML dari server.

---

## 2. Layer Overview

| Layer | Teknologi | Tanggung Jawab |
|---|---|---|
| **Public Web** | Blade + Tailwind CSS + Alpine.js | Rendering halaman publik |
| **Admin/CMS** | Filament v3 | CRUD konten, dashboard, user management |
| **Routing** | Laravel Router | Mendistribusikan request ke controller |
| **Controller** | Laravel Controllers | Menerima request, mengambil data, mengembalikan view |
| **Model** | Eloquent ORM | Representasi tabel, relasi, query scope, accessor |
| **Database** | MySQL 8.x | Penyimpanan data, schema via migration |
| **Media** | Laravel Filesystem + Cloudinary | Upload, storage, dan serving media |
| **Auth** | Laravel Auth + Filament Shield/Policy | Login admin, role, permission |
| **Assets** | Vite | Bundling CSS/JS untuk production |

---

## 3. Public Web Layer

### 3.1 Prinsip

- Server-rendered Blade templates.
- Tailwind CSS untuk styling (mobile-first).
- Alpine.js hanya untuk interaksi ringan (dropdown, mobile menu, lightbox).
- Tidak ada client-side routing atau SPA behavior.

### 3.2 Struktur Controller

Satu controller per domain modul. Tidak perlu base controller kustom.

```
app/Http/Controllers/
├── HomeController.php
├── NewsController.php
├── AnnouncementController.php
├── AgendaController.php
├── AchievementController.php
├── GalleryController.php
├── DocumentController.php
├── DigitalServiceController.php
├── PageController.php
├── StaffController.php
└── ContactController.php
```

Setiap controller cukup menggunakan method standar Laravel: `index()`, `show()`.

Tidak ada controller untuk create/update/delete di public — semua mutasi data melalui Filament admin.

### 3.3 Contoh Pola Controller

```php
class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()
            ->with('category', 'author')
            ->latest('published_at')
            ->paginate(12);

        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        abort_unless($news->isPublished(), 404);

        $related = News::published()
            ->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->limit(4)
            ->get();

        return view('news.show', compact('news', 'related'));
    }
}
```

Query scope (`published()`) didefinisikan di Model — bukan di controller.

### 3.4 Struktur View

```
resources/views/
├── layouts/
│   └── app.blade.php              # Layout utama publik
├── components/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   ├── hero-slider.blade.php
│   ├── news-card.blade.php
│   ├── announcement-card.blade.php
│   ├── staff-card.blade.php
│   ├── achievement-card.blade.php
│   ├── service-card.blade.php
│   ├── gallery-album-card.blade.php
│   ├── document-row.blade.php
│   ├── stat-counter.blade.php
│   ├── section-heading.blade.php
│   ├── breadcrumb.blade.php
│   ├── seo-meta.blade.php
│   └── pagination.blade.php
├── home/
│   └── index.blade.php
├── news/
│   ├── index.blade.php
│   └── show.blade.php
├── announcements/
│   ├── index.blade.php
│   └── show.blade.php
├── agenda/
│   ├── index.blade.php
│   └── show.blade.php
├── achievements/
│   ├── index.blade.php
│   └── show.blade.php
├── gallery/
│   ├── index.blade.php
│   └── show.blade.php
├── documents/
│   └── index.blade.php
├── services/
│   └── index.blade.php
├── staff/
│   └── index.blade.php
├── pages/
│   └── show.blade.php             # Halaman statis dinamis
├── contact/
│   └── index.blade.php
└── errors/
    ├── 404.blade.php
    └── 500.blade.php
```

---

## 4. Admin/CMS Layer (Filament)

### 4.1 Prinsip

- Filament v3 menangani seluruh admin panel di route `/admin`.
- Setiap modul konten = 1 Filament Resource.
- Filament menyediakan CRUD, form, table, dashboard widget secara built-in.
- Tidak perlu membangun admin UI secara manual dengan Blade.

### 4.2 Struktur Filament Resource

```
app/Filament/Resources/
├── NewsResource.php
├── NewsCategoryResource.php
├── AnnouncementResource.php
├── AgendaResource.php
├── StaffResource.php
├── AchievementResource.php
├── GalleryResource.php
├── GalleryPhotoResource.php       # Atau RelationManager di GalleryResource
├── DocumentResource.php
├── DigitalServiceResource.php
├── PageResource.php
├── BannerResource.php
├── SchoolStatisticResource.php
├── SiteSettingResource.php
└── UserResource.php
```

### 4.3 Dashboard

```
app/Filament/Widgets/
├── ContentStatsWidget.php         # Jumlah berita, pengumuman, GTK, dll.
├── LatestNewsWidget.php
└── UpcomingAgendaWidget.php
```

### 4.4 Navigasi Admin

Filament navigation dikelompokkan:

| Grup | Resources |
|---|---|
| **Konten** | Berita, Kategori, Pengumuman, Agenda, Halaman Statis |
| **Data Sekolah** | GTK, Prestasi, Galeri, Layanan Digital |
| **Tampilan** | Banner, Statistik Sekolah |
| **Pengaturan** | Site Settings, User Management |

---

## 5. Database/Model Layer

### 5.1 Prinsip

- Schema **hanya** melalui Laravel migration. Tidak ada perubahan manual.
- Eloquent model sebagai representasi tabel.
- Relasi, scope, dan accessor didefinisikan di model.
- Tidak menggunakan repository pattern — Eloquent langsung di controller/resource.

### 5.2 Entity Relationship

```
users ──────────────< news
news_categories ────< news
galleries ──────────< gallery_photos
```

Entitas lain (announcements, agendas, achievements, documents, digital_services, pages, banners, school_statistics, site_settings, staff) berdiri sendiri tanpa relasi kompleks.

### 5.3 Daftar Model dan Tabel

| Model | Tabel | Relasi Utama |
|---|---|---|
| `User` | `users` | hasMany News |
| `NewsCategory` | `news_categories` | hasMany News |
| `News` | `news` | belongsTo User, belongsTo NewsCategory |
| `Announcement` | `announcements` | — |
| `Agenda` | `agendas` | — |
| `Staff` | `teachers_staff` | — |
| `Achievement` | `achievements` | — |
| `Gallery` | `galleries` | hasMany GalleryPhoto |
| `GalleryPhoto` | `gallery_photos` | belongsTo Gallery |
| `Document` | `documents` | — |
| `DigitalService` | `digital_services` | — |
| `Page` | `pages` | — |
| `Banner` | `banners` | — |
| `SchoolStatistic` | `school_statistics` | — |
| `SiteSetting` | `site_settings` | — |

### 5.4 Pola Model

Setiap model mendefinisikan:

```php
class News extends Model
{
    // Mass assignment
    protected $fillable = [...];

    // Casting
    protected $casts = [
        'published_at' => 'datetime',
        'status' => NewsStatus::class,  // Enum
    ];

    // Relasi
    public function category(): BelongsTo { ... }
    public function author(): BelongsTo { ... }

    // Scope
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    // Accessor (untuk cloud media URL jika perlu)
    protected function featuredImageUrl(): Attribute { ... }
}
```

### 5.5 Enum untuk Status

Gunakan PHP 8.1+ backed enum:

```php
enum NewsStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
```

### 5.6 SiteSetting: Pola Key-Value

`site_settings` menggunakan pola key-value sederhana:

| Column | Type |
|---|---|
| `key` | string (unique) |
| `value` | text (nullable) |
| `type` | string (text, image, textarea, json) |

Diakses melalui helper global:

```php
// Helper function (didaftarkan di helpers.php atau AppServiceProvider)
function site_setting(string $key, $default = null)
{
    return SiteSetting::getValue($key, $default);
}
```

Cached secara keseluruhan karena jarang berubah.

---

## 6. Media Storage Layer

### 6.1 Strategi

| Environment | Storage | Keterangan |
|---|---|---|
| **Local dev** | `public` disk | File disimpan di `storage/app/public`, di-serve via symlink |
| **Production** | Cloudinary | File di-upload ke Cloudinary, URL disimpan di database |

### 6.2 Implementasi

Laravel Filesystem abstraction memungkinkan switching disk tanpa mengubah kode:

```
.env (local)
FILESYSTEM_DISK=public

.env (production)
FILESYSTEM_DISK=cloudinary
CLOUDINARY_URL=cloudinary://...
```

### 6.3 Database Storage

Database menyimpan **path/identifier** media, bukan binary:

```
# Kolom di tabel news
featured_image VARCHAR(500)  -- "news/2026/09/judul-berita.jpg"
```

Untuk menampilkan URL, model menggunakan accessor yang memanggil `Storage::url()`.

### 6.4 Upload via Filament

Filament `FileUpload` component sudah mendukung custom disk:

```php
FileUpload::make('featured_image')
    ->disk(config('filesystems.default'))
    ->directory('news')
    ->image()
    ->maxSize(2048)
    ->imageResizeMode('cover')
```

### 6.5 Media yang Menggunakan Cloud

Berdasarkan PRD, prioritas cloud storage:

- Foto GTK
- Featured image berita
- Galeri foto
- Banner
- Foto prestasi
- Dokumen (PDF, dll)

---

## 7. Routes

### 7.1 Public Routes (`routes/web.php`)

```php
// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Berita
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/berita/kategori/{category:slug}', [NewsController::class, 'byCategory'])->name('news.category');

// Pengumuman
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/pengumuman/{announcement:slug}', [AnnouncementController::class, 'show'])->name('announcements.show');

// Agenda
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda:slug}', [AgendaController::class, 'show'])->name('agenda.show');

// Prestasi
Route::get('/prestasi', [AchievementController::class, 'index'])->name('achievements.index');
Route::get('/prestasi/{achievement:slug}', [AchievementController::class, 'show'])->name('achievements.show');

// Galeri
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{gallery:slug}', [GalleryController::class, 'show'])->name('gallery.show');

// Dokumen
Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');

// Layanan Digital
Route::get('/layanan-digital', [DigitalServiceController::class, 'index'])->name('services.index');

// GTK
Route::get('/profil/guru-karyawan', [StaffController::class, 'index'])->name('staff.index');

// Halaman Statis (profil, visi-misi, dll)
Route::get('/profil/{page:slug}', [PageController::class, 'show'])->name('pages.show');

// Kontak (Hanya menampilkan info statis, tidak ada penyimpanan pesan di V1)
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
```

### 7.2 Admin Routes

Filament secara otomatis mendaftarkan semua admin route di bawah `/admin`. Tidak perlu mendefinisikan route secara manual.

### 7.3 Route Model Binding

Semua public detail page menggunakan `slug` sebagai route key:

```php
// Di model
public function getRouteKeyName(): string
{
    return 'slug';
}
```

---

## 8. Authorization

### 8.1 Struktur

| Concern | Mekanisme |
|---|---|
| **Public website** | Tidak ada auth. Semua halaman publik. |
| **Admin panel** | Filament auth guard. Login wajib. |
| **Role & Permission** | Filament Shield atau Spatie Permission (via Filament plugin). |

### 8.2 Role V1

| Role | Akses |
|---|---|
| **Super Admin** | Akses penuh ke semua resource dan settings |
| **Admin** | Akses penuh ke konten, tidak bisa manage users |
| **Editor** | Bisa membuat dan mengedit berita, pengumuman |
| **Kontributor** | Bisa membuat draft, tidak bisa publish |

### 8.3 Implementasi

V1 cukup menggunakan Filament Shield yang menyediakan permission per-resource secara otomatis. Tidak perlu membangun authorization system custom.

Permission granularity yang dihasilkan:

```
view_news, view_any_news, create_news, update_news, delete_news
view_announcement, view_any_announcement, ...
```

### 8.4 Catatan Penting

PRD menyatakan: *"Detail permission dapat disederhanakan pada awal build selama arsitektur mendukung penambahan permission selanjutnya."*

Artinya V1 boleh mulai dengan Super Admin dan Admin saja. Role Editor dan Kontributor ditambahkan setelah CRUD konten stabil.

---

## 9. Service Layer

### 9.1 Kapan Digunakan

**Tidak perlu service class untuk V1** pada kasus-kasus berikut:
- CRUD sederhana → langsung Eloquent di controller/Filament resource.
- Query dengan scope → didefinisikan di model.
- Formatting data → accessor/mutator di model.

**Gunakan service class** hanya jika:
- Ada logic bisnis yang dipakai di lebih dari satu tempat (controller + Filament + seeder).
- Ada proses multi-step yang melibatkan beberapa model.
- Logic terlalu kompleks untuk ditempatkan di controller.

### 9.2 Contoh yang Mungkin Dibutuhkan

```
app/Services/
└── MediaService.php    # Jika ada logic upload/transform yang kompleks
```

Untuk V1, kemungkinan besar **tidak ada service class yang dibutuhkan**. Evaluasi ulang saat implementasi.

---

## 10. Reusable Blade Components

### 10.1 Prinsip

- Gunakan Blade component (`<x-component>`) untuk elemen UI yang berulang.
- Component menerima data via props, bukan query database sendiri.
- Setiap component self-contained: markup + styling.
- Jangan menaruh business logic di component.

### 10.2 Komponen Inti

| Component | Digunakan di | Props |
|---|---|---|
| `<x-navbar>` | Semua halaman | — (baca dari site_settings) |
| `<x-footer>` | Semua halaman | — (baca dari site_settings) |
| `<x-news-card>` | Home, news index, related | `$news` |
| `<x-announcement-card>` | Home, announcements index | `$announcement` |
| `<x-staff-card>` | Staff index | `$staff` |
| `<x-achievement-card>` | Home, achievements index | `$achievement` |
| `<x-service-card>` | Home, services index | `$service` |
| `<x-gallery-album-card>` | Home, gallery index | `$gallery` |
| `<x-document-row>` | Documents index | `$document` |
| `<x-hero-slider>` | Home | `$banners` |
| `<x-stat-counter>` | Home | `$label`, `$value`, `$icon` |
| `<x-section-heading>` | Semua section homepage | `$title`, `$subtitle`, `$link` |
| `<x-breadcrumb>` | Semua halaman detail | `$items` |
| `<x-seo-meta>` | Semua halaman | `$title`, `$description`, `$image`, `$url` |
| `<x-pagination>` | Semua listing | `$paginator` |

### 10.3 Layout

Satu layout utama yang digunakan seluruh halaman publik:

```html
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <x-seo-meta :title="$title" :description="$description" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />
    <main>
        {{ $slot }}
    </main>
    <x-footer />
</body>
</html>
```

---

## 11. Folder Structure V1

```
web-smaba/
├── app/
│   ├── Enums/                     # PHP enums (NewsStatus, ServiceStatus, dll)
│   ├── Filament/
│   │   ├── Resources/             # Filament CRUD resources
│   │   └── Widgets/               # Dashboard widgets
│   ├── Http/
│   │   └── Controllers/           # Public controllers (read-only)
│   ├── Models/                    # Eloquent models
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   └── filesystems.php            # Disk config (local + cloudinary)
├── database/
│   ├── factories/                 # Model factories untuk testing/seeding
│   ├── migrations/                # Source of truth schema
│   └── seeders/                   # Data awal (kategori, site settings, admin user)
├── docs/
│   ├── PRD.md                     # Source of truth produk
│   └── ARCHITECTURE.md            # Dokumen ini
├── public/                        # Document root (Hostinger)
├── resources/
│   ├── css/
│   │   └── app.css                # Tailwind directives + custom styles
│   ├── js/
│   │   └── app.js                 # Alpine.js + minimal JS
│   └── views/
│       ├── components/            # Reusable Blade components
│       ├── layouts/               # Base layouts
│       ├── home/
│       ├── news/
│       ├── announcements/
│       ├── agenda/
│       ├── achievements/
│       ├── gallery/
│       ├── documents/
│       ├── services/
│       ├── staff/
│       ├── pages/
│       ├── contact/
│       └── errors/
├── routes/
│   └── web.php                    # Public routes
├── storage/
├── AGENTS.md
├── composer.json
├── package.json
├── tailwind.config.js
└── vite.config.js
```

---

## 12. Deployment — Hostinger

### 12.1 Constraint Hostinger

| Constraint | Dampak |
|---|---|
| Document root harus diarahkan ke `/public` | Pastikan `.htaccess` di public/ benar |
| Tidak ada root SSH (shared hosting) | Deployment via Git + manual setup |
| PHP version terbatas | Pastikan 8.2+ tersedia |
| Tidak ada Redis | Gunakan `database` atau `file` untuk cache/session/queue |
| Cron terbatas | Satu cron entry untuk `schedule:run` |
| Storage terbatas | Alasan utama menggunakan cloud media |

### 12.2 Environment Config

```env
# Production .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://smanegeri1babatlmg.sch.id

DB_CONNECTION=mysql
DB_HOST=<hostinger-mysql-host>
DB_DATABASE=<db-name>
DB_USERNAME=<db-user>
DB_PASSWORD=<db-password>

FILESYSTEM_DISK=cloudinary

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 12.3 Production Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --optimize-autoloader --no-dev
npm run build
```

### 12.4 Cron

Satu entry cron di Hostinger:

```
* * * * * cd /path/to/web-smaba && php artisan schedule:run >> /dev/null 2>&1
```

### 12.5 Storage Link

```bash
php artisan storage:link
```

Jika `storage:link` bermasalah di Hostinger, buat symlink manual atau gunakan route untuk serving file lokal.

---

## 13. Apa yang Tidak Digunakan

| Pattern/Tool | Alasan Tidak Digunakan |
|---|---|
| **Repository Pattern** | Eloquent sudah cukup. Tidak ada kebutuhan swap database driver. |
| **Service Layer (generik)** | Controller V1 cukup tipis. Tambahkan hanya jika muncul duplikasi logic. |
| **Event/Listener Architecture** | Tidak ada proses async yang kompleks di V1. |
| **API Layer** | Tidak ada consumer (mobile app/SPA). |
| **Queue (async)** | V1 menggunakan `sync`. Queue hanya jika ada proses berat (bulk email). |
| **Caching Layer (aggressive)** | Mulai tanpa cache. Tambahkan jika ada bottleneck terukur. |
| **Multi-tenancy** | Website single-tenant. |
| **Microservices** | Monolith sederhana sudah cukup untuk skala sekolah. |

---

## 14. Keputusan Arsitektur

| Keputusan | Pilihan | Alasan |
|---|---|---|
| Admin panel | Filament v3 | Built-in CRUD, dashboard, auth. Tidak perlu build admin UI manual. |
| Media cloud | Cloudinary | Free tier cukup untuk V1. Laravel integration tersedia. |
| Auth system | Filament built-in + Shield | Cukup untuk role-based access V1. |
| Database driver | MySQL | Sesuai PRD dan Hostinger compatibility. |
| Session/Cache driver | `file` (production) | Hostinger tidak punya Redis. `file` driver paling stabil. |
| Queue driver | `sync` (V1) | Tidak ada background job yang kritis di V1. |
| CSS framework | Tailwind CSS | Sudah ter-bundle di Laravel 11. Mobile-first by design. |
| JS framework | Alpine.js (minimal) | Sudah dibawa Filament. Cukup untuk dropdown, toggle, lightbox. |
| Deployment | Git pull + manual artisan | Kompatibel dengan Hostinger. CI/CD bisa ditambahkan nanti. |
