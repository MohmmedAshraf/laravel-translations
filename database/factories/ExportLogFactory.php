<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\Translations\Models\ExportLog;

class ExportLogFactory extends Factory
{
    protected $model = ExportLog::class;

    public function definition(): array
    {
        return [
            'locale_count' => fake()->numberBetween(1, 10),
            'file_count' => fake()->numberBetween(1, 20),
            'key_count' => fake()->numberBetween(10, 500),
            'duration_ms' => fake()->numberBetween(100, 15000),
            'triggered_by' => null,
            'source' => 'cli',
            'notes' => null,
        ];
    }

    public function fromUi(): static
    {
        return $this->state(fn () => [
            'source' => 'ui',
        ]);
    }
}
