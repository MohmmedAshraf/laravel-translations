<?php

namespace Outhebox\Translations\Services\Importer;

class ImportResult
{
    public function __construct(
        public int $localeCount = 0,
        public int $keyCount = 0,
        public int $newCount = 0,
        public int $updatedCount = 0,
        public int $durationMs = 0,
    ) {}

    public function merge(self $other): void
    {
        $this->localeCount += $other->localeCount;
        $this->keyCount += $other->keyCount;
        $this->newCount += $other->newCount;
        $this->updatedCount += $other->updatedCount;
    }
}
