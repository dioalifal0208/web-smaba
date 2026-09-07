<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ContentStatus;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_index_filters_content_orders_and_paginates(): void
    {
        Carbon::setTestNow('2026-09-07 12:00:00 UTC');
        foreach (range(1, 11) as $number) {
            Article::factory()->published()->create(['title' => "Berita [$number]", 'published_at' => now()->subMinutes(12 - $number)]);
        }
        Article::factory()->create(['title' => 'Draf']);
        Article::factory()->published()->create(['title' => 'Masa Depan', 'published_at' => now()->addMinute()]);
        $deleted = Article::factory()->published()->create(['title' => 'Terhapus']);
        $deleted->delete();
        $this->get(route('articles.index'))->assertOk()->assertSeeInOrder(['Berita [11]', 'Berita [10]'])->assertDontSee('Berita [1]')->assertDontSee('Draf')->assertDontSee('Masa Depan')->assertDontSee('Terhapus')->assertSee('?page=2', false);
        $this->get(route('articles.index', ['page' => 2]))->assertSee('Berita [1]');
    }

    public function test_index_empty_state_and_body_are_not_rendered(): void
    {
        Article::factory()->published()->create(['title' => 'Aman', 'body' => 'ARTICLE_BODY_SENTINEL']);
        $this->get(route('articles.index'))->assertOk()->assertSee('Aman')->assertDontSee('ARTICLE_BODY_SENTINEL')->assertSee('Berita');
        Article::query()->delete();
        $this->get(route('articles.index'))->assertSee('Belum ada berita.');
    }

    public function test_show_filters_invalid_content_and_sanitizes_body(): void
    {
        Carbon::setTestNow('2026-09-07 23:00:00 UTC');
        $article = Article::factory()->published()->create(['slug' => 'aman', 'published_at' => now(), 'body' => '<p>Paragraf aman</p><h2>Judul aman</h2><ul><li>Item aman</li></ul><a href="https://example.test">Tautan aman</a><script>buruk</script><p onclick="buruk" onerror="buruk">Teks aman</p><a href="javascript:buruk">Teks javascript</a>']);
        $this->get(route('articles.show', $article->slug))->assertOk()->assertSee('Paragraf aman')->assertSee('<h2>Judul aman</h2>', false)->assertSee('<li>Item aman</li>', false)->assertSee('href="https://example.test"', false)->assertSee('Teks aman')->assertDontSee('<script>buruk</script>', false)->assertDontSee('onclick="buruk"', false)->assertDontSee('onerror="buruk"', false)->assertDontSee('javascript:buruk', false)->assertSee('08 September 2026')->assertSee('<h1', false);
        foreach ([['draft', ContentStatus::Draft, now()->subDay()], ['null', ContentStatus::Published, null], ['future', ContentStatus::Published, now()->addDay()]] as [$slug, $status, $date]) {
            Article::factory()->create(['slug' => $slug, 'status' => $status, 'published_at' => $date]);
        }
        foreach (['draft', 'null', 'future', 'missing'] as $slug) {
            $this->get(route('articles.show', $slug))->assertNotFound();
        }
        $deleted = Article::factory()->published()->create(['slug' => 'deleted']);
        $deleted->delete();
        $this->get(route('articles.show', 'deleted'))->assertNotFound();
    }

    public function test_show_escapes_title_and_excerpt_metadata(): void
    {
        $article = Article::factory()->published()->create(['slug' => 'escaped', 'title' => '"</title><script>title</script>', 'excerpt' => '"</meta><script>excerpt</script>']);
        $this->get(route('articles.show', $article->slug))->assertOk()->assertDontSee('<script>title</script>', false)->assertDontSee('<script>excerpt</script>', false)->assertSee('&lt;script&gt;title&lt;/script&gt;', false);
    }
}
