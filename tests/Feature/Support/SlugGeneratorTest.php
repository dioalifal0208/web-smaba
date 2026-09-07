<?php

namespace Tests\Feature\Support;

use App\Models\Announcement;
use App\Models\Article;
use App\Support\SlugGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SlugGeneratorTest extends TestCase
{
    use RefreshDatabase {
        refreshDatabase as private refreshSqliteDatabase;
    }

    public function refreshDatabase(): void
    {
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        $this->refreshSqliteDatabase();
    }

    public function test_normal_title_is_slugged(): void
    {
        $this->assertSame('kegiatan-sekolah', SlugGenerator::generate(Article::class, 'Kegiatan Sekolah!', 'berita'));
    }

    public function test_collision_adds_numeric_suffix(): void
    {
        Article::factory()->create(['slug' => 'kegiatan']);
        $this->assertSame('kegiatan-2', SlugGenerator::generate(Article::class, 'Kegiatan', 'berita'));
    }

    public function test_multiple_collisions_increment_suffix(): void
    {
        foreach (['kegiatan', 'kegiatan-2', 'kegiatan-3'] as $slug) {
            Article::factory()->create(['slug' => $slug]);
        }
        $this->assertSame('kegiatan-4', SlugGenerator::generate(Article::class, 'Kegiatan', 'berita'));
    }

    #[DataProvider('fallbackCases')]
    public function test_empty_slug_uses_deterministic_fallback(string $model, string $fallback, string $title): void
    {
        foreach ([$fallback, $fallback.'-2', $fallback.'-3'] as $expected) {
            $slug = SlugGenerator::generate($model, $title, $fallback);
            $this->assertSame($expected, $slug);
            $model::factory()->create(['slug' => $slug]);
        }
    }

    public static function fallbackCases(): array
    {
        return [
            'empty article' => [Article::class, 'berita', ''],
            'punctuation article' => [Article::class, 'berita', '!!!'],
            'empty announcement' => [Announcement::class, 'pengumuman', ''],
        ];
    }

    public function test_slug_and_suffix_never_exceed_255_characters(): void
    {
        $title = str_repeat('a', 300);
        $base = str_repeat('a', 255);
        $this->assertSame($base, SlugGenerator::generate(Article::class, $title, 'berita'));
        Article::factory()->create(['slug' => $base]);

        foreach (range(2, 12) as $suffix) {
            $slug = SlugGenerator::generate(Article::class, $title, 'berita');
            $this->assertSame(255, strlen($slug));
            $this->assertSame(substr($base, 0, 255 - strlen('-'.$suffix)).'-'.$suffix, $slug);
            Article::factory()->create(['slug' => $slug]);
        }
    }

    public function test_soft_deleted_records_still_reserve_their_slug(): void
    {
        $record = Article::factory()->create(['slug' => 'kegiatan']);
        $record->delete();

        $this->assertSame('kegiatan-2', SlugGenerator::generate(Article::class, 'Kegiatan', 'berita'));
    }

    public function test_model_tables_have_independent_slug_namespaces(): void
    {
        Article::factory()->create(['slug' => 'kegiatan']);
        $this->assertSame('kegiatan', SlugGenerator::generate(Announcement::class, 'Kegiatan', 'pengumuman'));
    }

    public function test_invalid_model_class_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugGenerator::generate(\stdClass::class, 'Judul', 'berita');
    }

    public function test_empty_fallback_is_rejected_when_title_cannot_be_slugged(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugGenerator::generate(Article::class, '', '!!!');
    }
}
