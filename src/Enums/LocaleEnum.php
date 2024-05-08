<?php

namespace Outhebox\TranslationsUI\Enums;

enum LocaleEnum: string
{
    case english = 'en';
    case indonesian = 'id';

    public function label(): string
    {
        return match ($this) {
            self::english => 'English',
            self::indonesian => 'Indonesian',
        };
    }

    public static function fromLabel($label): self
    {
        return match ($label) {
            'English', 'english' => self::english,
            'Indonesian', 'indonesian' => self::indonesian,
            default => self::english,
        };
    }

    public static function toSelectArray(): array
    {
        return collect(self::cases())->map(function (LocaleEnum $lang) {
            return [
                'code' => $lang->value,
                'name' => $lang->label(),
            ];
        })->toArray();
    }
}
