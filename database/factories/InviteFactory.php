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
            'email' => $this->faker->email(),
            'token' => $this->faker->uuid(),
            'role' => $this->faker->randomElement(RoleEnum::cases()),
        ];
    }
}
