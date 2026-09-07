<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationGroup = 'Konten Publik';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static ?string $modelLabel = 'Pengumuman';

    protected static ?string $pluralModelLabel = 'Pengumuman';

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
                    ->helperText('Slug dibuat otomatis saat pengumuman dibuat dan tidak berubah ketika judul diedit.')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Ringkasan Singkat')
                    ->maxLength(1000)
                    ->rows(4),
                Forms\Components\RichEditor::make('body')
                    ->label('Isi Pengumuman')
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
                Forms\Components\Toggle::make('is_important')
                    ->label('Tandai sebagai Penting')
                    ->default(false),
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
                    ->helperText('Wajib diisi ketika status Dipublikasikan atau waktu kedaluwarsa diisi.')
                    ->timezone('Asia/Jakarta')
                    ->seconds(false)
                    ->native(true)
                    ->required(fn (Get $get): bool => self::isPublishedStatus($get('status')) || filled($get('expires_at'))),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Waktu Kedaluwarsa')
                    ->helperText('Jika diisi, waktunya harus setelah Waktu Publikasi.')
                    ->timezone('Asia/Jakarta')
                    ->seconds(false)
                    ->native(true)
                    ->live(onBlur: true)
                    ->after('published_at')
                    ->validationMessages([
                        'after' => 'Waktu Kedaluwarsa harus setelah Waktu Publikasi.',
                    ]),
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
                Tables\Columns\IconColumn::make('is_important')
                    ->label('Penting')
                    ->boolean(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis'),
                Tables\Columns\TextColumn::make('publication_state')
                    ->label('Kondisi')
                    ->badge()
                    ->getStateUsing(fn (Announcement $record): string => self::publicationState($record))
                    ->color(fn (string $state): string => match ($state) {
                        'Draf' => 'gray',
                        'Terjadwal' => 'warning',
                        'Aktif' => 'success',
                        'Kedaluwarsa' => 'danger',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Waktu Publikasi')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Waktu Kedaluwarsa')
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
                Tables\Filters\TernaryFilter::make('is_important')
                    ->label('Penting')
                    ->trueLabel('Hanya penting')
                    ->falseLabel('Tidak penting')
                    ->placeholder('Semua pengumuman'),
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }

    public static function publicationState(Announcement $record): string
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

        if ($record->expires_at !== null && $record->expires_at->lessThanOrEqualTo(now())) {
            return 'Kedaluwarsa';
        }

        return 'Aktif';
    }

    public static function isPublishedStatus(mixed $state): bool
    {
        if ($state instanceof ContentStatus) {
            return $state === ContentStatus::Published;
        }

        return $state === ContentStatus::Published->value;
    }
}
