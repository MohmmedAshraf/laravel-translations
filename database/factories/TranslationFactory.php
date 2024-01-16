<?php

namespace Outhebox\TranslationsUI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Translation;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'language_id' => Language::factory(),
            'source' => $this->faker->boolean(),
        ];
    }

    public function source(): self
    {
        return $this->state([
            'source' => true,
        ]);
    }
}
