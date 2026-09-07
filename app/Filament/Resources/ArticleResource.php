<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationGroup = 'Konten Publik';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Slug dibuat otomatis saat berita dibuat dan tidak berubah ketika judul diedit.')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Ringkasan Singkat')
                    ->maxLength(1000)
                    ->rows(4),
                Forms\Components\RichEditor::make('body')
                    ->label('Isi Berita')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'blockquote',
                        'h2',
                        'h3',
                        'link',
                        'undo',
                        'redo',
                    ]),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        ContentStatus::Draft->value => 'Draf',
                        ContentStatus::Published->value => 'Dipublikasikan',
                    ])
                    ->default(ContentStatus::Draft->value)
                    ->live()
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Waktu Publikasi')
                    ->helperText('Wajib diisi ketika status Dipublikasikan. Waktu masa depan digunakan sebagai jadwal.')
                    ->timezone('Asia/Jakarta')
                    ->seconds(false)
                    ->native(true)
                    ->required(fn (Get $get): bool => self::isPublishedStatus($get('status'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where(function (Builder $query) use ($search): void {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('excerpt', 'like', "%{$search}%");
                    })),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis'),
                Tables\Columns\TextColumn::make('publication_state')
                    ->label('Kondisi')
                    ->badge()
                    ->getStateUsing(fn (Article $record): string => self::publicationState($record))
                    ->color(fn (string $state): string => match ($state) {
                        'Draf' => 'gray',
                        'Terjadwal' => 'warning',
                        'Dipublikasikan' => 'success',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Waktu Publikasi')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        ContentStatus::Draft->value => 'Draf',
                        ContentStatus::Published->value => 'Dipublikasikan',
                    ]),
                Tables\Filters\TrashedFilter::make()
                    ->label('Konten Dihapus')
                    ->placeholder('Tanpa konten dihapus')
                    ->trueLabel('Semua konten')
                    ->falseLabel('Hanya konten dihapus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function publicationState(Article $record): string
    {
        if ($record->status !== ContentStatus::Published) {
            return 'Draf';
        }

        if ($record->published_at === null) {
            return 'Belum dijadwalkan';
        }

        if ($record->published_at->isFuture()) {
            return 'Terjadwal';
        }

        return 'Dipublikasikan';
    }

    public static function isPublishedStatus(mixed $state): bool
    {
        if ($state instanceof ContentStatus) {
            return $state === ContentStatus::Published;
        }

        return $state === ContentStatus::Published->value;
    }
}
