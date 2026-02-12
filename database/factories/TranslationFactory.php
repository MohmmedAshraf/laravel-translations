<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'translation_key_id' => TranslationKey::factory(),
            'language_id' => Language::factory(),
            'value' => fake()->sentence(),
            'status' => TranslationStatus::Translated,
        ];
    }

    public function untranslated(): static
    {
        return $this->state(fn () => [
            'value' => null,
            'status' => TranslationStatus::Untranslated,
        ]);
    }

    public function needsReview(): static
    {
        return $this->state(fn () => [
            'status' => TranslationStatus::NeedsReview,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => TranslationStatus::Approved,
        ]);
    }
}
