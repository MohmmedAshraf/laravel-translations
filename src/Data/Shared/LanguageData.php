<?php

namespace Outhebox\LaravelTranslations\Data\Shared;

use Spatie\LaravelData\Data;

class LanguageData extends Data
{
    public function __construct(
        public string $name,
        public string $code,
        public int $progress,
        public bool $source,
    ) {
    }
}
