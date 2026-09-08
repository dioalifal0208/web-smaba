<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\DigitalServiceAccessType;
use App\Enums\DigitalServiceStatus;
use Database\Factories\DigitalServiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalService extends Model
{
    /** @use HasFactory<DigitalServiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'name',
        'description',
        'url',
        'category',
        'publication_status',
        'operational_status',
        'access_type',
        'show_on_homepage',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'publication_status' => ContentStatus::class,
            'operational_status' => DigitalServiceStatus::class,
            'access_type' => DigitalServiceAccessType::class,
            'show_on_homepage' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('publication_status', ContentStatus::Published->value)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeShownOnHomepage(Builder $query): Builder
    {
        return $query->where('show_on_homepage', true);
    }
}
