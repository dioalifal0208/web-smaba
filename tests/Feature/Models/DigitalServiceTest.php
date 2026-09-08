<?php

namespace Tests\Feature\Models;

use App\Enums\ContentStatus;
use App\Enums\DigitalServiceAccessType;
use App\Enums\DigitalServiceStatus;
use App\Models\DigitalService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DigitalServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-09-08 08:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_suite_uses_sqlite_in_memory_database(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
    }

    public function test_factory_creates_digital_service(): void
    {
        $service = DigitalService::factory()->create();

        $this->assertDatabaseHas('digital_services', [
            'id' => $service->id,
            'publication_status' => ContentStatus::Draft->value,
        ]);
    }

    public function test_digital_service_status_values_and_labels_match_the_contract(): void
    {
        $statuses = [
            DigitalServiceStatus::Active->value => DigitalServiceStatus::Active->label(),
            DigitalServiceStatus::Maintenance->value => DigitalServiceStatus::Maintenance->label(),
            DigitalServiceStatus::Seasonal->value => DigitalServiceStatus::Seasonal->label(),
            DigitalServiceStatus::Development->value => DigitalServiceStatus::Development->label(),
            DigitalServiceStatus::Archived->value => DigitalServiceStatus::Archived->label(),
        ];

        $this->assertSame([
            'active' => 'Aktif',
            'maintenance' => 'Maintenance',
            'seasonal' => 'Musiman',
            'development' => 'Pengembangan',
            'archived' => 'Arsip',
        ], $statuses);
    }

    public function test_digital_service_access_type_values_and_labels_match_the_contract(): void
    {
        $accessTypes = [
            DigitalServiceAccessType::Public->value => DigitalServiceAccessType::Public->label(),
            DigitalServiceAccessType::Internal->value => DigitalServiceAccessType::Internal->label(),
            DigitalServiceAccessType::LimitedPublic->value => DigitalServiceAccessType::LimitedPublic->label(),
            DigitalServiceAccessType::TeachersOnly->value => DigitalServiceAccessType::TeachersOnly->label(),
            DigitalServiceAccessType::StudentsOnly->value => DigitalServiceAccessType::StudentsOnly->label(),
            DigitalServiceAccessType::AdminsOnly->value => DigitalServiceAccessType::AdminsOnly->label(),
        ];

        $this->assertSame([
            'public' => 'Publik',
            'internal' => 'Internal',
            'limited_public' => 'Publik Terbatas',
            'teachers_only' => 'Khusus Guru',
            'students_only' => 'Khusus Siswa',
            'admins_only' => 'Khusus Admin',
        ], $accessTypes);
    }

    public function test_attributes_are_cast_to_the_expected_types(): void
    {
        $service = DigitalService::factory()->create([
            'publication_status' => ContentStatus::Published,
            'operational_status' => DigitalServiceStatus::Active,
            'access_type' => DigitalServiceAccessType::Public,
            'show_on_homepage' => 1,
            'sort_order' => '12',
            'published_at' => now(),
        ])->refresh();

        $this->assertSame(ContentStatus::Published, $service->publication_status);
        $this->assertSame(DigitalServiceStatus::Active, $service->operational_status);
        $this->assertSame(DigitalServiceAccessType::Public, $service->access_type);
        $this->assertIsBool($service->show_on_homepage);
        $this->assertTrue($service->show_on_homepage);
        $this->assertIsInt($service->sort_order);
        $this->assertSame(12, $service->sort_order);
        $this->assertInstanceOf(Carbon::class, $service->published_at);
    }

    public function test_digital_service_belongs_to_author(): void
    {
        $author = User::factory()->create();
        $service = DigitalService::factory()->create(['author_id' => $author->id]);

        $this->assertTrue($service->author->is($author));
    }

    public function test_deleting_author_nulls_digital_service_author_id(): void
    {
        $author = User::factory()->create();
        $service = DigitalService::factory()->create(['author_id' => $author->id]);

        $author->delete();

        $this->assertDatabaseHas('digital_services', [
            'id' => $service->id,
            'author_id' => null,
        ]);
    }

    public function test_database_defaults_are_applied(): void
    {
        $service = DigitalService::query()->create([
            'name' => 'Layanan pengujian',
            'operational_status' => DigitalServiceStatus::Development,
            'access_type' => DigitalServiceAccessType::Internal,
        ])->refresh();

        $this->assertSame(ContentStatus::Draft, $service->publication_status);
        $this->assertFalse($service->show_on_homepage);
        $this->assertSame(0, $service->sort_order);
    }

    public function test_nullable_url_description_and_category_can_be_saved(): void
    {
        $service = DigitalService::factory()->create([
            'url' => null,
            'description' => null,
            'category' => null,
        ]);

        $this->assertDatabaseHas('digital_services', [
            'id' => $service->id,
            'url' => null,
            'description' => null,
            'category' => null,
        ]);
    }

    public function test_published_scope_excludes_draft_null_and_future_services(): void
    {
        $draft = DigitalService::factory()->create();
        $withoutPublicationDate = DigitalService::factory()->create([
            'publication_status' => ContentStatus::Published,
            'published_at' => null,
        ]);
        $future = DigitalService::factory()->create([
            'publication_status' => ContentStatus::Published,
            'published_at' => now()->addMinute(),
        ]);

        $this->assertFalse(DigitalService::published()->whereKey($draft)->exists());
        $this->assertFalse(DigitalService::published()->whereKey($withoutPublicationDate)->exists());
        $this->assertFalse(DigitalService::published()->whereKey($future)->exists());
    }

    public function test_published_scope_includes_past_and_exactly_now_services(): void
    {
        $past = DigitalService::factory()->create([
            'publication_status' => ContentStatus::Published,
            'published_at' => now()->subMinute(),
        ]);
        $atNow = DigitalService::factory()->create([
            'publication_status' => ContentStatus::Published,
            'published_at' => now(),
        ]);

        $this->assertTrue(DigitalService::published()->whereKey($past)->exists());
        $this->assertTrue(DigitalService::published()->whereKey($atNow)->exists());
    }

    public function test_shown_on_homepage_scope_only_filters_the_homepage_flag(): void
    {
        $hidden = DigitalService::factory()->create(['show_on_homepage' => false]);
        $draft = DigitalService::factory()->create([
            'show_on_homepage' => true,
            'publication_status' => ContentStatus::Draft,
            'sort_order' => 20,
        ]);
        $future = DigitalService::factory()->create([
            'show_on_homepage' => true,
            'publication_status' => ContentStatus::Published,
            'published_at' => now()->addDay(),
            'sort_order' => 1,
        ]);

        $this->assertEqualsCanonicalizing(
            [$draft->id, $future->id],
            DigitalService::shownOnHomepage()->pluck('id')->all(),
        );
        $this->assertFalse(DigitalService::shownOnHomepage()->whereKey($hidden)->exists());
        $this->assertStringNotContainsString('publication_status', DigitalService::shownOnHomepage()->toSql());
        $this->assertStringNotContainsString('order by', DigitalService::shownOnHomepage()->toSql());
        $this->assertStringNotContainsString('limit', DigitalService::shownOnHomepage()->toSql());
    }

    public function test_soft_deleted_service_can_be_found_with_trashed(): void
    {
        $service = DigitalService::factory()->create();

        $service->delete();

        $this->assertFalse(DigitalService::whereKey($service)->exists());
        $this->assertTrue(DigitalService::withTrashed()->whereKey($service)->exists());
    }

    public function test_carbon_test_time_can_be_cleared(): void
    {
        $this->assertNotNull(Carbon::getTestNow());

        Carbon::setTestNow();

        $this->assertNull(Carbon::getTestNow());
        Carbon::setTestNow(Carbon::parse('2026-09-08 08:00:00'));
    }
}
