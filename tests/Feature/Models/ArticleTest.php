<?php

namespace Tests\Feature\Models;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-09-07 08:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_factory_creates_article(): void
    {
        $article = Article::factory()->create();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'status' => ContentStatus::Draft->value,
        ]);
    }

    public function test_status_is_cast_to_content_status_enum(): void
    {
        $article = Article::factory()->create([
            'status' => ContentStatus::Published,
        ]);

        $this->assertSame(ContentStatus::Published, $article->refresh()->status);
    }

    public function test_article_belongs_to_author(): void
    {
        $author = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $author->id,
        ]);

        $this->assertTrue($article->author->is($author));
    }

    public function test_deleting_author_keeps_article_and_nulls_author_id(): void
    {
        $author = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $author->id,
        ]);

        $author->delete();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'author_id' => null,
        ]);
    }

    public function test_draft_article_is_not_returned_by_published_scope(): void
    {
        $article = Article::factory()->create();

        $this->assertFalse(Article::published()->whereKey($article)->exists());
    }

    public function test_published_article_without_published_at_is_not_returned_by_published_scope(): void
    {
        $article = Article::factory()->create([
            'status' => ContentStatus::Published,
            'published_at' => null,
        ]);

        $this->assertFalse(Article::published()->whereKey($article)->exists());
    }

    public function test_future_published_at_is_not_returned_by_published_scope(): void
    {
        $article = Article::factory()->create([
            'status' => ContentStatus::Published,
            'published_at' => now()->addDay(),
        ]);

        $this->assertFalse(Article::published()->whereKey($article)->exists());
    }

    public function test_published_article_with_past_published_at_is_returned_by_published_scope(): void
    {
        $article = Article::factory()->published()->create();

        $this->assertTrue(Article::published()->whereKey($article)->exists());
    }

    public function test_article_soft_deletes_and_can_be_found_with_trashed(): void
    {
        $article = Article::factory()->create();

        $article->delete();

        $this->assertFalse(Article::whereKey($article)->exists());
        $this->assertTrue(Article::withTrashed()->whereKey($article)->exists());
    }

    public function test_slug_must_be_unique(): void
    {
        Article::factory()->create(['slug' => 'duplikat-slug']);

        $this->expectException(QueryException::class);

        Article::factory()->create(['slug' => 'duplikat-slug']);
    }
}
