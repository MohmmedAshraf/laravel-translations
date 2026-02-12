<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\TranslationKey;

class TranslationKeyFactory extends Factory
{
    protected $model = TranslationKey::class;

    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'key' => fake()->unique()->words(3, true),
            'parameters' => null,
            'is_html' => false,
            'is_plural' => false,
        ];
    }

    public function withParameters(array $params = [':name', ':count']): static
    {
        return $this->state(fn () => [
            'parameters' => $params,
        ]);
    }

    public function withHtml(): static
    {
        return $this->state(fn () => [
            'is_html' => true,
        ]);
    }

    public function plural(): static
    {
        return $this->state(fn () => [
            'is_plural' => true,
        ]);
    }
}
