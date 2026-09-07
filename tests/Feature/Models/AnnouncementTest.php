<?php

namespace Tests\Feature\Models;

use App\Enums\ContentStatus;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AnnouncementTest extends TestCase
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

    public function test_factory_creates_announcement(): void
    {
        $announcement = Announcement::factory()->create();

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'status' => ContentStatus::Draft->value,
            'is_important' => false,
        ]);
    }

    public function test_status_and_is_important_are_cast(): void
    {
        $announcement = Announcement::factory()->important()->create([
            'status' => ContentStatus::Published,
        ]);

        $announcement->refresh();

        $this->assertSame(ContentStatus::Published, $announcement->status);
        $this->assertIsBool($announcement->is_important);
        $this->assertTrue($announcement->is_important);
    }

    public function test_announcement_belongs_to_author(): void
    {
        $author = User::factory()->create();
        $announcement = Announcement::factory()->create([
            'author_id' => $author->id,
        ]);

        $this->assertTrue($announcement->author->is($author));
    }

    public function test_deleting_author_keeps_announcement_and_nulls_author_id(): void
    {
        $author = User::factory()->create();
        $announcement = Announcement::factory()->create([
            'author_id' => $author->id,
        ]);

        $author->delete();

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'author_id' => null,
        ]);
    }

    public function test_draft_announcement_is_not_returned_by_published_scope(): void
    {
        $announcement = Announcement::factory()->create();

        $this->assertFalse(Announcement::published()->whereKey($announcement)->exists());
    }

    public function test_published_announcement_without_published_at_is_not_returned_by_published_scope(): void
    {
        $announcement = Announcement::factory()->create([
            'status' => ContentStatus::Published,
            'published_at' => null,
        ]);

        $this->assertFalse(Announcement::published()->whereKey($announcement)->exists());
    }

    public function test_future_published_at_is_not_returned_by_published_scope(): void
    {
        $announcement = Announcement::factory()->create([
            'status' => ContentStatus::Published,
            'published_at' => now()->addDay(),
        ]);

        $this->assertFalse(Announcement::published()->whereKey($announcement)->exists());
    }

    public function test_published_announcement_with_past_published_at_is_returned_by_published_scope(): void
    {
        $announcement = Announcement::factory()->published()->create();

        $this->assertTrue(Announcement::published()->whereKey($announcement)->exists());
    }

    public function test_expired_announcement_is_not_returned_by_active_scope(): void
    {
        $announcement = Announcement::factory()->expired()->create();

        $this->assertFalse(Announcement::active()->whereKey($announcement)->exists());
    }

    public function test_announcement_without_expires_at_is_returned_by_active_scope(): void
    {
        $announcement = Announcement::factory()->create([
            'expires_at' => null,
        ]);

        $this->assertTrue(Announcement::active()->whereKey($announcement)->exists());
    }

    public function test_announcement_with_future_expires_at_is_returned_by_active_scope(): void
    {
        $announcement = Announcement::factory()->create([
            'expires_at' => now()->addDay(),
        ]);

        $this->assertTrue(Announcement::active()->whereKey($announcement)->exists());
    }

    public function test_published_and_active_scopes_work_together(): void
    {
        $announcement = Announcement::factory()->published()->create([
            'expires_at' => now()->addDay(),
        ]);

        $this->assertTrue(Announcement::published()->active()->whereKey($announcement)->exists());
    }

    public function test_announcement_soft_deletes_and_can_be_found_with_trashed(): void
    {
        $announcement = Announcement::factory()->create();

        $announcement->delete();

        $this->assertFalse(Announcement::whereKey($announcement)->exists());
        $this->assertTrue(Announcement::withTrashed()->whereKey($announcement)->exists());
    }

    public function test_slug_must_be_unique(): void
    {
        Announcement::factory()->create(['slug' => 'duplikat-slug']);

        $this->expectException(QueryException::class);

        Announcement::factory()->create(['slug' => 'duplikat-slug']);
    }
}
