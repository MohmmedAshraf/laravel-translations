<?php

namespace Outhebox\LaravelTranslations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\LaravelTranslations\Models\TranslationFile;

class TranslationFileFactory extends Factory
{
    protected $model = TranslationFile::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'extension' => $this->faker->fileExtension(),
        ];
    }
}
