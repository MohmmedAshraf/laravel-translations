<?php

namespace Outhebox\LaravelTranslations\Enums;

enum RoleEnum: int
{
    case owner = 1;
    case reviewer = 2;
    case translator = 3;
    case translator_manager = 4;

    public function label(): string
    {
        return match ($this) {
            self::owner => 'Owner',
            self::reviewer => 'Reviewer',
            self::translator => 'Translator',
            self::translator_manager => 'Translator Manager',
        };
    }
}
