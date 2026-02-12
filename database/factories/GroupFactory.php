<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\Translations\Models\Group;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'namespace' => null,
            'file_format' => 'php',
        ];
    }

    public function json(): static
    {
        return $this->state(fn () => [
            'name' => '_json',
            'file_format' => 'json',
        ]);
    }

    public function vendor(string $namespace = 'vendor-package'): static
    {
        return $this->state(fn () => [
            'namespace' => $namespace,
        ]);
    }
}
