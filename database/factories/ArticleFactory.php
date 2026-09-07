<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'author_id' => null,
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => 'Ringkasan netral untuk kebutuhan pengujian otomatis.',
            'body' => 'Isi konten netral untuk kebutuhan pengujian otomatis.',
            'status' => ContentStatus::Draft,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Published,
            'published_at' => now()->subDay(),
        ]);
    }
}
