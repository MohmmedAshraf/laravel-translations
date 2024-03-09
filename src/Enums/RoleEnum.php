<?php

namespace Outhebox\TranslationsUI\Enums;

enum RoleEnum: int
{
    case owner = 1;
    case translator = 2;

    public function label(): string
    {
        return match ($this) {
            self::owner => 'Owner',
            self::translator => 'Translator',
        };
    }

    public static function fromLabel($label): self
    {
        return match ($label) {
            'Owner', 'owner' => self::owner,
            'Translator', 'translator' => self::translator,
            default => self::owner,
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::owner => 'Full access to everything',
            self::translator => 'Can translate phrases for a language or multiple languages',
        };
    }

    public static function toSelectArray(): array
    {
        return collect(self::cases())->map(function (RoleEnum $role) {
            return [
                'value' => $role->value,
                'label' => $role->label(),
                'description' => $role->description(),
            ];
        })->toArray();
    }
}
