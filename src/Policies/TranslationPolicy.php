<?php

namespace Outhebox\TranslationsUI\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Outhebox\TranslationsUI\Enums\RoleEnum;
use Outhebox\TranslationsUI\Models\Contributor;
use Outhebox\TranslationsUI\Models\Translation;

class TranslationPolicy
{
    use HandlesAuthorization;

    public function viewAny(Contributor $user): bool
    {
        return false;
    }

    public function view(Contributor $user, Translation $translation): bool
    {
        return $user->role === RoleEnum::owner || $user->languages->contains($translation->language);
    }

    public function create(Contributor $user): bool
    {
        return $user->role === RoleEnum::owner;
    }

    public function update(Contributor $user, Translation $translation): bool
    {
        return $user->role === RoleEnum::owner || $user->languages->contains($translation->language);
    }

    public function delete(Contributor $user, Translation $translation): bool
    {
        return $user->role === RoleEnum::owner;
    }
}
