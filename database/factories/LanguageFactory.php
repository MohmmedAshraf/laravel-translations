<?php

namespace Outhebox\TranslationsUI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\TranslationsUI\Models\Language;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'rtl' => $this->faker->boolean(),
            'code' => $this->faker->randomElement(['en', 'nl', 'fr', 'de', 'es', 'id', 'it', 'pt', 'ru', 'ja', 'zh']),
            'name' => $this->faker->randomElement(['English', 'Dutch', 'French', 'German', 'Spanish', 'Indonesian', 'Italian', 'Portuguese', 'Russian', 'Japanese', 'Chinese']),
        ];
    }
}
