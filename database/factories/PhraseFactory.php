<?php

namespace Outhebox\LaravelTranslations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use Outhebox\LaravelTranslations\Models\TranslationFile;

class PhraseFactory extends Factory
{
    protected $model = Phrase::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'phrase_id' => Phrase::factory(),
            'key' => $this->faker->unique()->word(),
            'translation_id' => Translation::factory(),
            'translation_file_id' => TranslationFile::factory(),
            'group' => $this->faker->word(),
            'value' => $this->faker->sentence(),
            'parameters' => [],
        ];
    }

    public function withParameters(): self
    {
        return $this->state([
            'parameters' => [
                'param1' => $this->faker->word(),
                'param2' => $this->faker->word(),
            ],
        ]);
    }
}
