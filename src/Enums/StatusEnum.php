<?php

namespace Outhebox\TranslationsUI\Enums;

enum StatusEnum: string
{
    case active = 'active';
    case inactive = 'inactive';
    case deprecated = 'deprecated';
    case needs_update = 'needs_update';

    public function label(): string
    {
        return match ($this) {
            self::active => 'Active',
            self::inactive => 'Inactive',
            self::deprecated => 'Deprecated',
            self::needs_update => 'Needs Update',
        };
    }
}
