<?php

namespace Outhebox\Translations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

class ContributorFactory extends Factory
{
    protected $model = Contributor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => ContributorRole::Translator,
            'is_active' => true,
        ];
    }

    public function owner(): static
    {
        return $this->state(fn () => [
            'role' => ContributorRole::Owner,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => ContributorRole::Admin,
        ]);
    }

    public function translator(): static
    {
        return $this->state(fn () => [
            'role' => ContributorRole::Translator,
        ]);
    }

    public function reviewer(): static
    {
        return $this->state(fn () => [
            'role' => ContributorRole::Reviewer,
        ]);
    }

    public function viewer(): static
    {
        return $this->state(fn () => [
            'role' => ContributorRole::Viewer,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }

    public function invited(?string $token = null): static
    {
        return $this->state(fn () => [
            'is_active' => false,
            'invite_token' => Hash::make($token ?? fake()->uuid()),
            'invite_expires_at' => now()->addDays(7),
        ]);
    }
}
