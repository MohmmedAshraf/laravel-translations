<?php

namespace Outhebox\Translations\Enums;

use Outhebox\Translations\Contracts\HasColor;
use Outhebox\Translations\Contracts\HasIcon;
use Outhebox\Translations\Contracts\HasLabel;

enum TranslationStatus: string implements HasColor, HasIcon, HasLabel
{
    case Untranslated = 'untranslated';
    case Translated = 'translated';
    case NeedsReview = 'needs_review';
    case Approved = 'approved';

    public function getLabel(): string
    {
        return match ($this) {
            self::Untranslated => 'Untranslated',
            self::Translated => 'Translated',
            self::NeedsReview => 'Needs Review',
            self::Approved => 'Approved',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Untranslated => 'neutral',
            self::Translated => 'green',
            self::NeedsReview => 'amber',
            self::Approved => 'blue',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Untranslated => 'translate',
            self::Translated => 'translate',
            self::NeedsReview => 'alert-circle',
            self::Approved => 'check',
        };
    }
}
