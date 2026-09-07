<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SlugGenerator
{
    public static function generate(string $modelClass, string $title, string $fallback): string
    {
        if (! is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException('Model class must be an Eloquent model.');
        }

        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = Str::slug($fallback);
        }

        if ($baseSlug === '') {
            throw new InvalidArgumentException('Fallback must produce a valid slug.');
        }

        $baseSlug = Str::limit($baseSlug, 255, '');
        $slug = $baseSlug;
        $suffix = 2;

        // Soft-deleted records still reserve their slug; the unique index guards concurrent writes.
        while ($modelClass::query()->withoutGlobalScope(SoftDeletingScope::class)->where('slug', $slug)->exists()) {
            $suffixText = '-'.$suffix;
            $slug = Str::limit($baseSlug, 255 - strlen($suffixText), '').$suffixText;
            $suffix++;
        }

        return $slug;
    }
}
