<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ContentStatus;
use App\Http\Controllers\HomeController;
use App\Models\Announcement;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_homepage_shows_empty_states_when_there_is_no_valid_content(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Belum ada berita terbaru.')
            ->assertSee('Belum ada pengumuman penting.');
    }

    public function test_draft_article_is_not_shown(): void
    {
        Article::factory()->create(['title' => 'Berita Draf', 'published_at' => now()->subDay()]);

        $this->get(route('home'))->assertDontSee('Berita Draf');
    }

    public function test_published_article_without_publication_date_is_not_shown(): void
    {
        Article::factory()->create(['title' => 'Berita Tanpa Jadwal', 'status' => ContentStatus::Published, 'published_at' => null]);

        $this->get(route('home'))->assertDontSee('Berita Tanpa Jadwal');
    }

    public function test_future_article_is_not_shown(): void
    {
        Article::factory()->published()->create(['title' => 'Berita Masa Depan', 'published_at' => now()->addMinute()]);

        $this->get(route('home'))->assertDontSee('Berita Masa Depan');
    }

    public function test_past_and_current_articles_are_shown(): void
    {
        Carbon::setTestNow('2026-09-07 12:00:00 UTC');
        Article::factory()->published()->create(['title' => 'Berita Lampau', 'published_at' => now()->subMinute()]);
        Article::factory()->published()->create(['title' => 'Berita Sekarang', 'published_at' => now()]);

        $this->get(route('home'))
            ->assertSee('Berita Lampau')
            ->assertSee('Berita Sekarang');
    }

    public function test_articles_are_ordered_newest_first_and_limited_to_four(): void
    {
        Carbon::setTestNow('2026-09-07 12:00:00 UTC');
        foreach (['Berita Kelima', 'Berita Keempat', 'Berita Ketiga', 'Berita Kedua', 'Berita Pertama'] as $offset => $title) {
            Article::factory()->published()->create(['title' => $title, 'published_at' => now()->subMinutes(5 - $offset)]);
        }

        $this->get(route('home'))
            ->assertSeeInOrder(['Berita Pertama', 'Berita Kedua', 'Berita Ketiga', 'Berita Keempat'])
            ->assertDontSee('Berita Kelima');
    }

    public function test_article_with_null_excerpt_renders_without_generated_text(): void
    {
        Article::factory()->published()->create([
            'title' => 'Berita Tanpa Ringkasan',
            'excerpt' => null,
            'body' => 'ARTICLE_SECRET_BODY_SENTINEL',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Berita Tanpa Ringkasan')
            ->assertDontSee('Ringkasan netral untuk kebutuhan pengujian otomatis.')
            ->assertDontSee('ARTICLE_SECRET_BODY_SENTINEL');
    }

    public function test_draft_announcement_is_not_shown(): void
    {
        Announcement::factory()->important()->create(['title' => 'Pengumuman Draf', 'published_at' => now()->subDay()]);

        $this->get(route('home'))->assertDontSee('Pengumuman Draf');
    }

    public function test_unimportant_announcement_is_not_shown(): void
    {
        Announcement::factory()->published()->create(['title' => 'Pengumuman Biasa']);

        $this->get(route('home'))->assertDontSee('Pengumuman Biasa');
    }

    public function test_announcement_without_publication_date_is_not_shown(): void
    {
        Announcement::factory()->important()->create([
            'title' => 'Pengumuman Tanpa Jadwal',
            'status' => ContentStatus::Published,
            'published_at' => null,
        ]);

        $this->get(route('home'))->assertDontSee('Pengumuman Tanpa Jadwal');
    }

    public function test_future_announcement_is_not_shown(): void
    {
        Announcement::factory()->published()->important()->create(['title' => 'Pengumuman Masa Depan', 'published_at' => now()->addMinute()]);

        $this->get(route('home'))->assertDontSee('Pengumuman Masa Depan');
    }

    public function test_active_announcements_with_and_without_expiration_are_shown(): void
    {
        Announcement::factory()->published()->important()->create(['title' => 'Pengumuman Belum Kedaluwarsa', 'expires_at' => now()->addDay()]);
        Announcement::factory()->published()->important()->create(['title' => 'Pengumuman Tanpa Kedaluwarsa', 'expires_at' => null]);

        $this->get(route('home'))
            ->assertSee('Pengumuman Belum Kedaluwarsa')
            ->assertSee('Pengumuman Tanpa Kedaluwarsa');
    }

    public function test_expired_announcements_are_not_shown_including_at_the_current_time(): void
    {
        Carbon::setTestNow('2026-09-07 12:00:00 UTC');
        Announcement::factory()->published()->important()->create(['title' => 'Pengumuman Kedaluwarsa', 'expires_at' => now()->subSecond()]);
        Announcement::factory()->published()->important()->create(['title' => 'Pengumuman Batas Kedaluwarsa', 'expires_at' => now()]);

        $this->get(route('home'))
            ->assertDontSee('Pengumuman Kedaluwarsa')
            ->assertDontSee('Pengumuman Batas Kedaluwarsa');
    }

    public function test_announcements_are_ordered_newest_first_and_limited_to_three(): void
    {
        Carbon::setTestNow('2026-09-07 12:00:00 UTC');
        foreach (['Pengumuman Keempat', 'Pengumuman Ketiga', 'Pengumuman Kedua', 'Pengumuman Pertama'] as $offset => $title) {
            Announcement::factory()->published()->important()->create(['title' => $title, 'published_at' => now()->subMinutes(4 - $offset)]);
        }

        $this->get(route('home'))
            ->assertSeeInOrder(['Pengumuman Pertama', 'Pengumuman Kedua', 'Pengumuman Ketiga'])
            ->assertDontSee('Pengumuman Keempat');
    }

    public function test_announcement_with_null_excerpt_renders_without_generated_text_or_body(): void
    {
        Announcement::factory()->published()->important()->create([
            'title' => 'Pengumuman Tanpa Ringkasan',
            'excerpt' => null,
            'body' => 'ANNOUNCEMENT_SECRET_BODY_SENTINEL',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Pengumuman Tanpa Ringkasan')
            ->assertDontSee('Ringkasan pengumuman netral untuk kebutuhan pengujian otomatis.')
            ->assertDontSee('ANNOUNCEMENT_SECRET_BODY_SENTINEL');
    }

    public function test_dates_are_converted_to_asia_jakarta_before_presentation(): void
    {
        Carbon::setTestNow('2026-09-07 23:30:00 UTC');
        Article::factory()->published()->create(['title' => 'Berita Pergantian Hari', 'published_at' => now()]);
        Announcement::factory()->published()->important()->create(['title' => 'Pengumuman Pergantian Hari', 'published_at' => now()]);

        $this->get(route('home'))
            ->assertSee('<time datetime="2026-09-08"', false)
            ->assertSee('08 September 2026')
            ->assertSee('Berita Pergantian Hari')
            ->assertSee('Pengumuman Pergantian Hari');
    }

    public function test_homepage_does_not_render_placeholder_or_empty_links_and_has_one_h1(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertDontSee('href="#"', false)
            ->assertDontSee('href=""', false)
            ->assertDontSee("href=''", false);

        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_home_route_is_named_and_uses_home_controller_index(): void
    {
        $route = Route::getRoutes()->getByName('home');

        $this->assertNotNull($route);
        $this->assertSame('/', $route->uri());
        $this->assertSame(HomeController::class.'@index', $route->getActionName());
    }
}
