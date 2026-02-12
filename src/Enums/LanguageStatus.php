<?php

namespace Outhebox\Translations\Enums;

use Outhebox\Translations\Contracts\HasColor;
use Outhebox\Translations\Contracts\HasIcon;
use Outhebox\Translations\Contracts\HasLabel;

enum LanguageStatus: string implements HasColor, HasIcon, HasLabel
{
    case Completed = 'completed';
    case InProgress = 'in_progress';
    case NeedsReview = 'needs_review';
    case NotStarted = 'not_started';

    public function getLabel(): string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::InProgress => 'In Progress',
            self::NeedsReview => 'Needs Review',
            self::NotStarted => 'Not Started',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Completed => 'green',
            self::InProgress => 'blue',
            self::NeedsReview => 'amber',
            self::NotStarted => 'neutral',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Completed => 'check-circle',
            self::InProgress => 'clock',
            self::NeedsReview => 'alert-circle',
            self::NotStarted => 'x-circle',
        };
    }
}
