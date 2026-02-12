<?php

namespace Outhebox\Translations\Database\Seeders;

use Illuminate\Database\Seeder;
use Outhebox\Translations\Database\Factories\ContributorFactory;

class ContributorSeeder extends Seeder
{
    public function run(): void
    {
        ContributorFactory::new()->owner()->create([
            'email' => 'admin@example.com',
            'name' => 'Admin',
        ]);
    }
}
