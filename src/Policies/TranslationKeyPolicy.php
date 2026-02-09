<?php

namespace Outhebox\Translations\Policies;

use Outhebox\Translations\Services\TranslationAuth;

class TranslationKeyPolicy
{
    public function __construct(
        protected TranslationAuth $auth,
    ) {}

    public function create(): bool
    {
        return $this->auth->role()?->canManageKeys() ?? false;
    }

    public function update(): bool
    {
        return $this->auth->role()?->canManageKeys() ?? false;
    }

    public function delete(): bool
    {
        return $this->auth->role()?->canManageKeys() ?? false;
    }
}
