<?php

namespace Outhebox\TranslationsUI\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Invite;

class InviteFactory extends Factory
{
    protected $model = Invite::class;

    public function definition(): array
    {
        return [
            'token' => $this->faker->uuid(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => $this->faker->randomElement(RoleEnum::cases()),
        ];
    }
}
