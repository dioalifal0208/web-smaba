<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Support\SlugGenerator;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $authorId = Filament::auth()->id();

        if ($authorId === null) {
            throw ValidationException::withMessages([
                'author_id' => 'Sesi admin tidak valid.',
            ]);
        }

        $data['author_id'] = $authorId;
        $data['slug'] = SlugGenerator::generate(Announcement::class, $data['title'] ?? '', 'pengumuman');

        return $data;
    }
}
