<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ContentStatus;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_index_filters_orders_and_paginates(): void
    {
        Carbon::setTestNow('2026-09-07 12:00:00 UTC');
        foreach (range(1, 11) as $number) {
            Announcement::factory()->published()->create(['title' => "Pengumuman [$number]", 'published_at' => now()->subMinutes(12 - $number), 'is_important' => false]);
        }
        Announcement::factory()->published()->important()->create(['title' => 'Penting', 'published_at' => now()->subDay()]);
        Announcement::factory()->published()->important()->create(['title' => 'Kedaluwarsa', 'expires_at' => now()]);
        $this->get(route('announcements.index'))->assertOk()->assertSeeInOrder(['Penting', 'Pengumuman [11]'])->assertDontSee('Pengumuman [1]')->assertDontSee('Kedaluwarsa')->assertSee('?page=2', false);
        $this->get(route('announcements.index', ['page' => 2]))->assertSee('Pengumuman [1]');
    }

    public function test_index_handles_empty_and_does_not_render_body(): void
    {
        Announcement::factory()->published()->create(['title' => 'Aktif', 'body' => 'ANNOUNCEMENT_BODY_SENTINEL', 'expires_at' => null]);
        $this->get(route('announcements.index'))->assertOk()->assertSee('Aktif')->assertDontSee('ANNOUNCEMENT_BODY_SENTINEL');
        Announcement::query()->delete();
        $this->get(route('announcements.index'))->assertSee('Belum ada pengumuman.');
    }

    public function test_show_filters_and_sanitizes_body(): void
    {
        Carbon::setTestNow('2026-09-07 23:00:00 UTC');
        $item = Announcement::factory()->published()->important()->create(['slug' => 'aman', 'published_at' => now(), 'expires_at' => null, 'body' => '<p>Paragraf aman</p><h2>Judul aman</h2><ul><li>Item aman</li></ul><a href="https://example.test">Tautan aman</a><script>buruk</script><p onclick="buruk" onerror="buruk">Teks aman</p><a href="javascript:buruk">Teks javascript</a>']);
        $this->get(route('announcements.show', $item->slug))->assertOk()->assertSee('Paragraf aman')->assertSee('<h2>Judul aman</h2>', false)->assertSee('<li>Item aman</li>', false)->assertSee('href="https://example.test"', false)->assertDontSee('<script>buruk</script>', false)->assertDontSee('onclick="buruk"', false)->assertDontSee('onerror="buruk"', false)->assertDontSee('javascript:buruk', false)->assertSee('08 September 2026');
        foreach ([['draft', ContentStatus::Draft, now()->subDay(), null], ['null', ContentStatus::Published, null, null], ['future', ContentStatus::Published, now()->addDay(), null], ['expired', ContentStatus::Published, now()->subDay(), now()->subSecond()], ['boundary', ContentStatus::Published, now()->subDay(), now()]] as [$slug, $status, $published, $expires]) {
            Announcement::factory()->create(['slug' => $slug, 'status' => $status, 'published_at' => $published, 'expires_at' => $expires]);
        }
        foreach (['draft', 'null', 'future', 'expired', 'boundary', 'missing'] as $slug) {
            $this->get(route('announcements.show', $slug))->assertNotFound();
        }
    }

    public function test_show_escapes_title_and_excerpt_metadata(): void
    {
        $item = Announcement::factory()->published()->create(['slug' => 'escaped', 'title' => '"</title><script>title</script>', 'excerpt' => '"</meta><script>excerpt</script>']);
        $this->get(route('announcements.show', $item->slug))->assertOk()->assertDontSee('<script>title</script>', false)->assertDontSee('<script>excerpt</script>', false)->assertSee('&lt;script&gt;title&lt;/script&gt;', false);
    }
}
