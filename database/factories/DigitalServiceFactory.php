<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Enums\DigitalServiceAccessType;
use App\Enums\DigitalServiceStatus;
use App\Models\DigitalService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DigitalService>
 */
class DigitalServiceFactory extends Factory
{
    protected $model = DigitalService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => null,
            'name' => fake()->unique()->words(3, true),
            'description' => 'Deskripsi netral untuk kebutuhan pengujian otomatis.',
            'url' => 'https://example.test/layanan',
            'category' => 'Kategori pengujian',
            'publication_status' => ContentStatus::Draft,
            'operational_status' => DigitalServiceStatus::Development,
            'access_type' => DigitalServiceAccessType::Internal,
            'show_on_homepage' => false,
            'sort_order' => 0,
            'published_at' => null,
        ];
    }
}
