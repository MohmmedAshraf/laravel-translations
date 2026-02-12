<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\Translations\Models\ImportLog;

class ImportLogFactory extends Factory
{
    protected $model = ImportLog::class;

    public function definition(): array
    {
        return [
            'locale_count' => fake()->numberBetween(1, 10),
            'key_count' => fake()->numberBetween(10, 500),
            'new_count' => fake()->numberBetween(0, 100),
            'updated_count' => fake()->numberBetween(0, 50),
            'duration_ms' => fake()->numberBetween(100, 30000),
            'triggered_by' => null,
            'source' => 'cli',
            'fresh' => false,
            'notes' => null,
        ];
    }

    public function fromUi(): static
    {
        return $this->state(fn () => [
            'source' => 'ui',
        ]);
    }

    public function fresh(): static
    {
        return $this->state(fn () => [
            'fresh' => true,
        ]);
    }
}
