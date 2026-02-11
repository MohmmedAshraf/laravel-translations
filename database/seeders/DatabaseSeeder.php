<?php

namespace Outhebox\Translations\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            ContributorSeeder::class,
        ]);
    }
}
