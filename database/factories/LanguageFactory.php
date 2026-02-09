<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\Translations\Models\Language;
use OverflowException;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        $languages = [
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'rtl' => false],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'rtl' => false],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'rtl' => false],
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'rtl' => true],
            ['code' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語', 'rtl' => false],
            ['code' => 'zh', 'name' => 'Chinese', 'native_name' => '中文', 'rtl' => false],
            ['code' => 'pt', 'name' => 'Portuguese', 'native_name' => 'Português', 'rtl' => false],
            ['code' => 'it', 'name' => 'Italian', 'native_name' => 'Italiano', 'rtl' => false],
            ['code' => 'ko', 'name' => 'Korean', 'native_name' => '한국어', 'rtl' => false],
            ['code' => 'nl', 'name' => 'Dutch', 'native_name' => 'Nederlands', 'rtl' => false],
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский', 'rtl' => false],
            ['code' => 'pl', 'name' => 'Polish', 'native_name' => 'Polski', 'rtl' => false],
            ['code' => 'sv', 'name' => 'Swedish', 'native_name' => 'Svenska', 'rtl' => false],
            ['code' => 'da', 'name' => 'Danish', 'native_name' => 'Dansk', 'rtl' => false],
            ['code' => 'fi', 'name' => 'Finnish', 'native_name' => 'Suomi', 'rtl' => false],
            ['code' => 'nb', 'name' => 'Norwegian', 'native_name' => 'Norsk', 'rtl' => false],
            ['code' => 'tr', 'name' => 'Turkish', 'native_name' => 'Türkçe', 'rtl' => false],
            ['code' => 'uk', 'name' => 'Ukrainian', 'native_name' => 'Українська', 'rtl' => false],
            ['code' => 'cs', 'name' => 'Czech', 'native_name' => 'Čeština', 'rtl' => false],
            ['code' => 'el', 'name' => 'Greek', 'native_name' => 'Ελληνικά', 'rtl' => false],
        ];

        try {
            $lang = fake()->unique()->randomElement($languages);

            return [
                'code' => $lang['code'],
                'name' => $lang['name'],
                'native_name' => $lang['native_name'],
                'rtl' => $lang['rtl'],
                'active' => true,
                'is_source' => false,
            ];
        } catch (OverflowException) {
            $code = fake()->unique()->lexify('??');

            return [
                'code' => $code,
                'name' => 'Language '.$code,
                'native_name' => 'Language '.$code,
                'rtl' => false,
                'active' => true,
                'is_source' => false,
            ];
        }
    }

    public function source(): static
    {
        return $this->state(fn () => [
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_source' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'active' => false,
        ]);
    }

    public function rtl(): static
    {
        return $this->state(fn () => [
            'code' => 'ar',
            'name' => 'Arabic',
            'native_name' => 'العربية',
            'rtl' => true,
        ]);
    }
}
