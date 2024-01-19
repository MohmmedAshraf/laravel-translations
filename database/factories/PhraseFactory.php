<?php

namespace Outhebox\TranslationsUI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

class PhraseFactory extends Factory
{
    protected $model = Phrase::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
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
                'param1',
                'param2',
            ],
        ]);
    }

    public function withSource(): self
    {
        return $this->state([
            'phrase_id' => Phrase::factory(),
        ]);
    }
}
