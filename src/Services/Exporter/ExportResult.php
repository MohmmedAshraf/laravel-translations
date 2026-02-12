<?php

namespace Outhebox\Translations\Services\Exporter;

class ExportResult
{
    public function __construct(
        public int $localeCount = 0,
        public int $fileCount = 0,
        public int $keyCount = 0,
        public int $durationMs = 0,
    ) {}
}
