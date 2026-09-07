<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use App\Support\SlugGenerator;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $authorId = Filament::auth()->id();

        if ($authorId === null) {
            throw ValidationException::withMessages([
                'author_id' => 'Sesi admin tidak valid.',
            ]);
        }

        $data['author_id'] = $authorId;
        $data['slug'] = SlugGenerator::generate(Article::class, $data['title'] ?? '', 'berita');

        return $data;
    }
}
