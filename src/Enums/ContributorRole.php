<?php

namespace Outhebox\Translations\Enums;

use Outhebox\Translations\Contracts\HasColor;
use Outhebox\Translations\Contracts\HasLabel;

enum ContributorRole: string implements HasColor, HasLabel
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Translator = 'translator';
    case Reviewer = 'reviewer';
    case Viewer = 'viewer';

    public function getLabel(): string
    {
        return match ($this) {
            self::Owner => 'Owner',
            self::Admin => 'Admin',
            self::Translator => 'Translator',
            self::Reviewer => 'Reviewer',
            self::Viewer => 'Viewer',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Owner => 'purple',
            self::Admin => 'blue',
            self::Reviewer => 'green',
            self::Translator => 'yellow',
            self::Viewer => 'gray',
        };
    }

    public function level(): int
    {
        return match ($this) {
            self::Owner => 100,
            self::Admin => 80,
            self::Reviewer => 60,
            self::Translator => 40,
            self::Viewer => 20,
        };
    }

    public function isAtLeast(self $role): bool
    {
        return $this->level() >= $role->level();
    }

    public function canEditTranslations(): bool
    {
        return in_array($this, [self::Owner, self::Admin, self::Translator]);
    }

    public function canApproveTranslations(): bool
    {
        return in_array($this, [self::Owner, self::Admin, self::Reviewer]);
    }

    public function canManageKeys(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canManageLanguages(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canManageContributors(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canImportExport(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canManageSettings(): bool
    {
        return $this === self::Owner;
    }

    public function canTranslate(): bool
    {
        return $this->canEditTranslations();
    }

    public function canReview(): bool
    {
        return $this->canApproveTranslations();
    }
}
