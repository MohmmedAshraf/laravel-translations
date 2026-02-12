<?php

namespace Outhebox\Translations\Policies;

use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Services\TranslationAuth;

class TranslationPolicy
{
    public function __construct(
        protected TranslationAuth $auth,
    ) {}

    public function update(Translation $translation): bool
    {
        $role = $this->auth->role();

        if (! $role?->canEditTranslations()) {
            return false;
        }

        if ($role === ContributorRole::Translator) {
            return $this->auth->isAssignedToLanguage($translation->language_id);
        }

        return true;
    }

    public function approve(Translation $translation): bool
    {
        return $this->canApprove();
    }

    public function reject(Translation $translation): bool
    {
        return $this->canApprove();
    }

    private function canApprove(): bool
    {
        return $this->auth->role()?->canApproveTranslations() ?? false;
    }
}
