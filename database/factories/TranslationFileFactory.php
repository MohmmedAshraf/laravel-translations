<?php

namespace Outhebox\TranslationsUI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\TranslationsUI\Models\TranslationFile;

class TranslationFileFactory extends Factory
{
    protected $model = TranslationFile::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['app', 'auth', 'pagination', 'passwords', 'validation', 'en']),
            'extension' => $this->faker->randomElement(['php', 'php', 'php', 'php', 'php', 'json']),
            'is_root' => $this->faker->randomElement([false, false, false, false, false, true]),
        ];
    }

    public function json(): self
    {
        return $this->state([
            'extension' => 'json',
        ]);
    }
}
