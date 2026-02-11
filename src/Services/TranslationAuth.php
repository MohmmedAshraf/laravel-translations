<?php

namespace Outhebox\Translations\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Outhebox\Translations\Contracts\TranslatableUser;
use Outhebox\Translations\Enums\AuthDriver;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Contributor;

class TranslationAuth
{
    protected ?Collection $cachedLanguageIds = null;

    public function __construct(
        protected Application $app,
    ) {}

    public function user(): ?TranslatableUser
    {
        $user = $this->app['auth']->guard($this->guardName())->user();

        if ($user instanceof TranslatableUser) {
            return $user;
        }

        return null;
    }

    public function id(): ?string
    {
        return $this->user()?->getTranslationId();
    }

    public function displayName(): ?string
    {
        return $this->user()?->getTranslationDisplayName();
    }

    public function role(): ?ContributorRole
    {
        $roleValue = $this->user()?->getTranslationRole();

        return $roleValue ? ContributorRole::tryFrom($roleValue) : null;
    }

    public function check(): bool
    {
        return $this->app['auth']->guard($this->guardName())->check();
    }

    public function isContributorMode(): bool
    {
        return config('translations.auth.driver') === AuthDriver::Contributors->value;
    }

    public function guardName(): string
    {
        return $this->isContributorMode() ? 'translations' : config('translations.auth.guard', 'web');
    }

    public function assignedLanguageIds(): Collection
    {
        if ($this->cachedLanguageIds !== null) {
            return $this->cachedLanguageIds;
        }

        $user = $this->user();

        return $this->cachedLanguageIds = $user instanceof Contributor
            ? $user->languages()->pluck('ltu_languages.id')
            : collect();
    }

    public function isAssignedToLanguage(int $languageId): bool
    {
        $role = $this->role();

        if (! $role) {
            return false;
        }

        if ($role->isAtLeast(ContributorRole::Admin)) {
            return true;
        }

        return $this->assignedLanguageIds()->contains($languageId);
    }

    public function canAccessLanguage(int $languageId): bool
    {
        return $this->isAssignedToLanguage($languageId);
    }
}
