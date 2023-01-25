<?php

namespace Outhebox\LaravelTranslations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\LaravelTranslations\Models\Language;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->randomElement(['en', 'nl', 'fr', 'de', 'es', 'it', 'pt', 'ru', 'ja', 'zh']),
            'name' => $this->faker->randomElement(['English', 'Dutch', 'French', 'German', 'Spanish', 'Italian', 'Portuguese', 'Russian', 'Japanese', 'Chinese']),
            'rtl' => $this->faker->boolean(),
        ];
    }
}
