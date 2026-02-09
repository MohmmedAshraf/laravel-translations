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
        if ($this->isContributorMode()) {
            return $this->app['auth']->guard('translations')->user();
        }

        $user = $this->app['auth']->guard(config('translations.auth.guard', 'web'))->user();

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
        return ContributorRole::tryFrom($this->user()?->getTranslationRole() ?? '');
    }

    public function check(): bool
    {
        if ($this->isContributorMode()) {
            return $this->app['auth']->guard('translations')->check();
        }

        return $this->app['auth']->guard(config('translations.auth.guard', 'web'))->check();
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

        if ($user instanceof Contributor) {
            $this->cachedLanguageIds = $user->languages()->pluck('ltu_languages.id');
        } else {
            $this->cachedLanguageIds = collect();
        }

        return $this->cachedLanguageIds;
    }

    public function isAssignedToLanguage(int $languageId): bool
    {
        $role = $this->role();

        if (! $role) {
            return false;
        }

        if ($role !== ContributorRole::Translator) {
            return true;
        }

        return $this->assignedLanguageIds()->contains($languageId);
    }

    public function canAccessLanguage(int $languageId): bool
    {
        return $this->isAssignedToLanguage($languageId);
    }
}
