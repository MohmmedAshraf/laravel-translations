<?php

namespace Outhebox\Translations\Enums;

use Outhebox\Translations\Contracts\HasColor;
use Outhebox\Translations\Contracts\HasLabel;

enum ContributorStatus: string implements HasColor, HasLabel
{
    case Invited = 'invited';
    case Active = 'active';
    case Inactive = 'inactive';

    public function getLabel(): string
    {
        return match ($this) {
            self::Invited => 'Invited',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Invited => 'yellow',
            self::Active => 'green',
            self::Inactive => 'red',
        };
    }
}
