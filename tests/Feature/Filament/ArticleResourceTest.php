<?php

namespace Tests\Feature\Filament;

use App\Enums\ContentStatus;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Pages\CreateArticle;
use App\Filament\Resources\ArticleResource\Pages\EditArticle;
use App\Filament\Resources\ArticleResource\Pages\ListArticles;
use App\Models\Article;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ArticleResourceTest extends TestCase
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
        $record = Article::factory()->create();

        foreach (['index', 'create', 'edit'] as $page) {
            $this->get(ArticleResource::getUrl($page, ['record' => $record]))
                ->assertRedirect(Filament::getLoginUrl());
        }
    }

    public function test_local_admin_can_access_resource_pages(): void
    {
        $this->actingAs(User::factory()->create());
        $record = Article::factory()->create();

        foreach (['index', 'create', 'edit'] as $page) {
            $this->get(ArticleResource::getUrl($page, ['record' => $record]))->assertOk();
        }
    }

    public function test_create_assigns_author_and_slug_server_side_and_accepts_draft(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $page = Livewire::test(CreateArticle::class)
            ->assertFormFieldDoesNotExist('author_id')
            ->assertFormFieldIsHidden('slug')
            ->fillForm($this->formData())
            ->set('data.slug', 'slug-palsu')
            ->set('data.author_id', User::factory()->create()->id)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertArrayNotHasKey('author_id', $page->instance()->form->getFlatFields());
        $record = Article::sole();
        $this->assertSame($admin->id, $record->author_id);
        $this->assertSame('judul-berita', $record->slug);
        $this->assertSame(ContentStatus::Draft, $record->status);
        $this->assertNull($record->published_at);
    }

    public function test_create_overwrites_untrusted_data_and_rejects_missing_authentication(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        $page = new CreateArticle;
        $method = new \ReflectionMethod($page, 'mutateFormDataBeforeCreate');
        $data = $method->invoke($page, $this->formData() + ['author_id' => 999, 'slug' => 'palsu']);
        $this->assertSame($admin->id, $data['author_id']);
        $this->assertSame('judul-berita', $data['slug']);

        Filament::auth()->logout();
        $this->expectException(ValidationException::class);
        $method->invoke($page, $this->formData());
    }

    public function test_duplicate_title_gets_a_suffix(): void
    {
        $this->actingAs(User::factory()->create());
        Article::factory()->create(['title' => 'Judul Berita', 'slug' => 'judul-berita']);

        Livewire::test(CreateArticle::class)
            ->fillForm($this->formData())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', ['title' => 'Judul Berita', 'slug' => 'judul-berita-2']);
    }

    public function test_edit_keeps_original_slug_and_author_even_with_tampered_state(): void
    {
        $author = User::factory()->create();
        $this->actingAs(User::factory()->create());
        $record = Article::factory()->create(['author_id' => $author->id, 'slug' => 'slug-asli']);

        Livewire::test(EditArticle::class, ['record' => $record->getRouteKey()])
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
        $record = $operation === 'edit' ? Article::factory()->create() : null;
        $page = Livewire::test(
            $record ? EditArticle::class : CreateArticle::class,
            $record ? ['record' => $record->getRouteKey()] : [],
        )->fillForm($this->formData([
            'status' => $status,
            'published_at' => $date,
        ]))->call($record ? 'save' : 'create');

        if (! $valid) {
            $page->assertHasFormErrors(['published_at' => 'required']);
            $this->assertSame($record ? 1 : 0, Article::count());
            if ($record) {
                $this->assertSame(ContentStatus::Draft, $record->refresh()->status);
            }

            return;
        }

        $page->assertHasNoFormErrors();
        $saved = Article::sole();
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
        $this->assertTrue(ArticleResource::isPublishedStatus(ContentStatus::Published));
        $this->assertTrue(ArticleResource::isPublishedStatus('published'));
        $this->assertFalse(ArticleResource::isPublishedStatus(ContentStatus::Draft));
        $this->assertFalse(ArticleResource::isPublishedStatus('draft'));
        $this->assertFalse(ArticleResource::isPublishedStatus(null));
    }

    public function test_edit_actions_soft_delete_and_restore(): void
    {
        $this->actingAs(User::factory()->create());
        $record = Article::factory()->create();

        Livewire::test(EditArticle::class, ['record' => $record->getRouteKey()])
            ->assertActionVisible('delete')
            ->assertActionHidden('restore')
            ->callAction('delete');

        $this->assertSoftDeleted($record);

        Livewire::test(EditArticle::class, ['record' => $record->getRouteKey()])
            ->assertActionHidden('delete')
            ->assertActionVisible('restore')
            ->callAction('restore');

        $this->assertNotSoftDeleted($record);
    }

    public function test_table_actions_and_bulk_actions_soft_delete_and_restore(): void
    {
        $this->actingAs(User::factory()->create());
        $record = Article::factory()->create();

        Livewire::test(ListArticles::class)->callTableAction('delete', $record);
        $this->assertSoftDeleted($record);
        Livewire::test(ListArticles::class)->filterTable('trashed', false)->callTableAction('restore', $record->refresh());
        $this->assertNotSoftDeleted($record);

        $records = Article::factory()->count(2)->create();
        Livewire::test(ListArticles::class)->callTableBulkAction('delete', $records);
        foreach ($records as $item) {
            $this->assertSoftDeleted($item);
        }
        Livewire::test(ListArticles::class)->filterTable('trashed', false)->callTableBulkAction('restore', $records);
        foreach ($records as $item) {
            $this->assertNotSoftDeleted($item);
        }
    }

    public function test_table_badges_and_body_is_not_rendered(): void
    {
        $this->actingAs(User::factory()->create());
        $draft = Article::factory()->create(['body' => '<strong>Isi privat tabel</strong>']);
        $scheduled = Article::factory()->published()->create(['published_at' => now()->addDay()]);
        $published = Article::factory()->published()->create(['published_at' => now()]);
        $unscheduled = Article::factory()->create(['status' => ContentStatus::Published]);

        Livewire::test(ListArticles::class)
            ->assertTableColumnStateSet('publication_state', 'Draf', $draft)
            ->assertTableColumnStateSet('publication_state', 'Terjadwal', $scheduled)
            ->assertTableColumnStateSet('publication_state', 'Dipublikasikan', $published)
            ->assertTableColumnStateSet('publication_state', 'Belum dijadwalkan', $unscheduled)
            ->assertTableColumnDoesNotExist('body')
            ->assertDontSee('Isi privat tabel');
    }

    public function test_table_searches_excerpt_and_filters_status_and_trashed(): void
    {
        $this->actingAs(User::factory()->create());
        $draft = Article::factory()->create(['excerpt' => 'frasaunik']);
        $published = Article::factory()->published()->create();
        $trashed = Article::factory()->create();
        $trashed->delete();

        Livewire::test(ListArticles::class)->searchTable('frasaunik')
            ->assertCanSeeTableRecords([$draft])->assertCanNotSeeTableRecords([$published, $trashed]);

        Livewire::test(ListArticles::class)->filterTable('status', 'published')
            ->assertCanSeeTableRecords([$published])->assertCanNotSeeTableRecords([$draft, $trashed]);

        Livewire::test(ListArticles::class)->filterTable('trashed', false)
            ->assertCanSeeTableRecords([$trashed])->assertCanNotSeeTableRecords([$draft, $published]);
    }

    public function test_rich_editor_toolbar_is_restricted(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateArticle::class)->assertFormFieldExists('body', function (RichEditor $field): bool {
            $this->assertSame([
                'bold', 'italic', 'underline', 'bulletList', 'orderedList', 'blockquote',
                'h2', 'h3', 'link', 'undo', 'redo',
            ], $field->getToolbarButtons());

            return true;
        });
    }

    private function formData(array $overrides = []): array
    {
        return array_replace([
            'title' => 'Judul Berita',
            'body' => '<p>Isi berita untuk pengujian.</p>',
            'status' => ContentStatus::Draft->value,
            'published_at' => null,
        ], $overrides);
    }
}
