<?php

namespace Tests\Feature\Filament;

use App\Enums\ContentStatus;
use App\Filament\Resources\AnnouncementResource;
use App\Filament\Resources\AnnouncementResource\Pages\CreateAnnouncement;
use App\Filament\Resources\AnnouncementResource\Pages\EditAnnouncement;
use App\Filament\Resources\AnnouncementResource\Pages\ListAnnouncements;
use App\Models\Announcement;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AnnouncementResourceTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();

        // The existing User model permits panel access only in the local environment.
        config(['app.env' => 'local']);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->travelTo(now()->setDate(2026, 9, 7)->setTime(8, 0));
    }

    public function test_guests_cannot_access_resource_pages(): void
    {
        $record = Announcement::factory()->create();

        foreach (['index', 'create', 'edit'] as $page) {
            $this->get(AnnouncementResource::getUrl($page, ['record' => $record]))
                ->assertRedirect(Filament::getLoginUrl());
        }
    }

    public function test_local_admin_can_access_resource_pages(): void
    {
        $this->actingAs(User::factory()->create());
        $record = Announcement::factory()->create();

        foreach (['index', 'create', 'edit'] as $page) {
            $this->get(AnnouncementResource::getUrl($page, ['record' => $record]))->assertOk();
        }
    }

    public function test_create_assigns_author_and_slug_server_side_and_accepts_draft(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $page = Livewire::test(CreateAnnouncement::class)
            ->assertFormFieldDoesNotExist('author_id')
            ->assertFormFieldIsHidden('slug')
            ->fillForm($this->formData())
            ->set('data.slug', 'slug-palsu')
            ->set('data.author_id', User::factory()->create()->id)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertArrayNotHasKey('author_id', $page->instance()->form->getFlatFields());
        $record = Announcement::sole();
        $this->assertSame($admin->id, $record->author_id);
        $this->assertSame('judul-pengumuman', $record->slug);
        $this->assertSame(ContentStatus::Draft, $record->status);
        $this->assertNull($record->published_at);
    }

    public function test_create_overwrites_untrusted_data_and_rejects_missing_authentication(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        $page = new CreateAnnouncement;
        $method = new \ReflectionMethod($page, 'mutateFormDataBeforeCreate');
        $data = $method->invoke($page, $this->formData() + ['author_id' => 999, 'slug' => 'palsu']);
        $this->assertSame($admin->id, $data['author_id']);
        $this->assertSame('judul-pengumuman', $data['slug']);

        Filament::auth()->logout();
        $this->expectException(ValidationException::class);
        $method->invoke($page, $this->formData());
    }

    public function test_duplicate_title_gets_a_suffix(): void
    {
        $this->actingAs(User::factory()->create());
        Announcement::factory()->create(['title' => 'Judul Pengumuman', 'slug' => 'judul-pengumuman']);

        Livewire::test(CreateAnnouncement::class)
            ->fillForm($this->formData())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('announcements', ['title' => 'Judul Pengumuman', 'slug' => 'judul-pengumuman-2']);
    }

    public function test_edit_keeps_original_slug_and_author_even_with_tampered_state(): void
    {
        $author = User::factory()->create();
        $this->actingAs(User::factory()->create());
        $record = Announcement::factory()->create(['author_id' => $author->id, 'slug' => 'slug-asli']);

        Livewire::test(EditAnnouncement::class, ['record' => $record->getRouteKey()])
            ->assertFormFieldDoesNotExist('author_id')
            ->assertFormFieldIsVisible('slug')
            ->assertFormFieldIsDisabled('slug')
            ->fillForm(['title' => 'Judul Baru'])
            ->set('data.slug', 'slug-palsu')
            ->set('data.author_id', auth()->id())
            ->call('save')
            ->assertHasNoFormErrors();

        $record->refresh();
        $this->assertSame('Judul Baru', $record->title);
        $this->assertSame('slug-asli', $record->slug);
        $this->assertSame($author->id, $record->author_id);
    }

    #[DataProvider('publicationCases')]
    public function test_publication_validation_on_create_and_edit(string $operation, string $status, ?string $date, bool $valid): void
    {
        $this->actingAs(User::factory()->create());
        $record = $operation === 'edit' ? Announcement::factory()->create() : null;
        $page = Livewire::test(
            $record ? EditAnnouncement::class : CreateAnnouncement::class,
            $record ? ['record' => $record->getRouteKey()] : [],
        )->fillForm($this->formData([
            'status' => $status,
            'published_at' => $date,
        ]))->call($record ? 'save' : 'create');

        if (! $valid) {
            $page->assertHasFormErrors(['published_at' => 'required']);
            $this->assertSame($record ? 1 : 0, Announcement::count());
            if ($record) {
                $this->assertSame(ContentStatus::Draft, $record->refresh()->status);
            }

            return;
        }

        $page->assertHasNoFormErrors();
        $saved = Announcement::sole();
        $this->assertSame($status, $saved->status->value);
        $this->assertSame($date, $saved->published_at?->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
    }

    public static function publicationCases(): array
    {
        $cases = [];
        foreach (['create', 'edit'] as $operation) {
            $cases[$operation.' draft'] = [$operation, 'draft', null, true];
            $cases[$operation.' missing date'] = [$operation, 'published', null, false];
            $cases[$operation.' now'] = [$operation, 'published', '2026-09-07 15:00:00', true];
            $cases[$operation.' future'] = [$operation, 'published', '2026-09-08 15:00:00', true];
        }

        return $cases;
    }

    public function test_status_comparison_accepts_enum_and_string(): void
    {
        $this->assertTrue(AnnouncementResource::isPublishedStatus(ContentStatus::Published));
        $this->assertTrue(AnnouncementResource::isPublishedStatus('published'));
        $this->assertFalse(AnnouncementResource::isPublishedStatus(ContentStatus::Draft));
        $this->assertFalse(AnnouncementResource::isPublishedStatus('draft'));
        $this->assertFalse(AnnouncementResource::isPublishedStatus(null));
    }

    public function test_edit_actions_soft_delete_and_restore(): void
    {
        $this->actingAs(User::factory()->create());
        $record = Announcement::factory()->create();

        Livewire::test(EditAnnouncement::class, ['record' => $record->getRouteKey()])
            ->assertActionVisible('delete')
            ->assertActionHidden('restore')
            ->callAction('delete');

        $this->assertSoftDeleted($record);

        Livewire::test(EditAnnouncement::class, ['record' => $record->getRouteKey()])
            ->assertActionHidden('delete')
            ->assertActionVisible('restore')
            ->callAction('restore');

        $this->assertNotSoftDeleted($record);
    }

    public function test_table_actions_and_bulk_actions_soft_delete_and_restore(): void
    {
        $this->actingAs(User::factory()->create());
        $record = Announcement::factory()->create();

        Livewire::test(ListAnnouncements::class)->callTableAction('delete', $record);
        $this->assertSoftDeleted($record);
        Livewire::test(ListAnnouncements::class)->filterTable('trashed', false)->callTableAction('restore', $record->refresh());
        $this->assertNotSoftDeleted($record);

        $records = Announcement::factory()->count(2)->create();
        Livewire::test(ListAnnouncements::class)->callTableBulkAction('delete', $records);
        foreach ($records as $item) {
            $this->assertSoftDeleted($item);
        }
        Livewire::test(ListAnnouncements::class)->filterTable('trashed', false)->callTableBulkAction('restore', $records);
        foreach ($records as $item) {
            $this->assertNotSoftDeleted($item);
        }
    }

    public function test_table_badges_and_body_is_not_rendered(): void
    {
        $this->actingAs(User::factory()->create());
        $draft = Announcement::factory()->create(['body' => '<strong>Isi privat tabel</strong>']);
        $scheduled = Announcement::factory()->published()->create(['published_at' => now()->addDay()]);
        $published = Announcement::factory()->published()->create(['published_at' => now()]);
        $expired = Announcement::factory()->published()->create(['expires_at' => now()]);
        $unscheduled = Announcement::factory()->create(['status' => ContentStatus::Published]);

        Livewire::test(ListAnnouncements::class)
            ->assertTableColumnStateSet('publication_state', 'Draf', $draft)
            ->assertTableColumnStateSet('publication_state', 'Terjadwal', $scheduled)
            ->assertTableColumnStateSet('publication_state', 'Aktif', $published)
            ->assertTableColumnStateSet('publication_state', 'Belum dijadwalkan', $unscheduled)
            ->assertTableColumnStateSet('publication_state', 'Kedaluwarsa', $expired)
            ->assertTableColumnDoesNotExist('body')
            ->assertDontSee('Isi privat tabel');
    }

    public function test_table_searches_excerpt_and_filters_status_and_trashed(): void
    {
        $this->actingAs(User::factory()->create());
        $draft = Announcement::factory()->create(['excerpt' => 'frasaunik']);
        $published = Announcement::factory()->published()->create();
        $trashed = Announcement::factory()->create();
        $trashed->delete();

        Livewire::test(ListAnnouncements::class)->searchTable('frasaunik')
            ->assertCanSeeTableRecords([$draft])->assertCanNotSeeTableRecords([$published, $trashed]);

        Livewire::test(ListAnnouncements::class)->filterTable('status', 'published')
            ->assertCanSeeTableRecords([$published])->assertCanNotSeeTableRecords([$draft, $trashed]);

        Livewire::test(ListAnnouncements::class)->filterTable('trashed', false)
            ->assertCanSeeTableRecords([$trashed])->assertCanNotSeeTableRecords([$draft, $published]);
    }

    public function test_rich_editor_toolbar_is_restricted(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateAnnouncement::class)->assertFormFieldExists('body', function (RichEditor $field): bool {
            $this->assertSame([
                'bold', 'italic', 'underline', 'bulletList', 'orderedList', 'blockquote',
                'h2', 'h3', 'link', 'undo', 'redo',
            ], $field->getToolbarButtons());

            return true;
        });
    }

    #[DataProvider('expirationCases')]
    public function test_expiration_validation_on_create_and_edit(string $operation, ?string $publishedAt, ?string $expiresAt, ?string $error): void
    {
        $this->actingAs(User::factory()->create());
        $record = $operation === 'edit' ? Announcement::factory()->create() : null;
        $page = Livewire::test(
            $record ? EditAnnouncement::class : CreateAnnouncement::class,
            $record ? ['record' => $record->getRouteKey()] : [],
        )->fillForm($this->formData([
            'published_at' => $publishedAt,
            'expires_at' => $expiresAt,
        ]))->call($record ? 'save' : 'create');

        if ($error !== null) {
            $page->assertHasFormErrors([$error]);
            $this->assertSame($record ? 1 : 0, Announcement::count());
            if ($record) {
                $this->assertNull($record->refresh()->expires_at);
            }

            return;
        }

        $page->assertHasNoFormErrors();
        $this->assertSame(
            $expiresAt,
            Announcement::sole()->expires_at?->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
        );
    }

    public static function expirationCases(): array
    {
        $cases = [];
        foreach (['create', 'edit'] as $operation) {
            $cases[$operation.' optional'] = [$operation, null, null, null];
            $cases[$operation.' missing publication'] = [$operation, null, '2026-09-08 15:00:00', 'published_at'];
            $cases[$operation.' equal'] = [$operation, '2026-09-07 15:00:00', '2026-09-07 15:00:00', 'expires_at'];
            $cases[$operation.' before'] = [$operation, '2026-09-07 15:00:00', '2026-09-07 14:00:00', 'expires_at'];
            $cases[$operation.' after'] = [$operation, '2026-09-07 15:00:00', '2026-09-07 16:00:00', null];
        }

        return $cases;
    }

    public function test_important_toggle_is_saved_and_can_be_filtered(): void
    {
        $this->actingAs(User::factory()->create());
        Livewire::test(CreateAnnouncement::class)
            ->fillForm($this->formData(['is_important' => true]))
            ->call('create')
            ->assertHasNoFormErrors();

        $important = Announcement::sole();
        $ordinary = Announcement::factory()->create();
        $this->assertTrue($important->is_important);

        Livewire::test(ListAnnouncements::class)
            ->assertTableColumnStateSet('is_important', true, $important)
            ->filterTable('is_important', true)
            ->assertCanSeeTableRecords([$important])
            ->assertCanNotSeeTableRecords([$ordinary]);

        Livewire::test(EditAnnouncement::class, ['record' => $important->getRouteKey()])
            ->fillForm(['is_important' => false])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertFalse($important->refresh()->is_important);
    }

    private function formData(array $overrides = []): array
    {
        return array_replace([
            'title' => 'Judul Pengumuman',
            'body' => '<p>Isi pengumuman untuk pengujian.</p>',
            'status' => ContentStatus::Draft->value,
            'published_at' => null,
        ], $overrides);
    }
}
