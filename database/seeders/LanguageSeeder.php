<?php

namespace Outhebox\Translations\Database\Seeders;

use Illuminate\Database\Seeder;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Support\LanguageDataProvider;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $existing = Language::query()->pluck('code');

        $new = collect($this->languages())
            ->reject(fn (array $language) => $existing->contains($language['code']))
            ->toArray();

        Language::insert($new);
    }

    public function languages(): array
    {
        return LanguageDataProvider::all();
    }
}
