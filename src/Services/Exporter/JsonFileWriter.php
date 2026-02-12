<?php

namespace Outhebox\Translations\Services\Exporter;

class JsonFileWriter
{
    public function write(string $filePath, array $translations, bool $sortKeys = true, bool $prettyPrint = true, bool $unescapeUnicode = true): bool
    {
        if ($sortKeys) {
            ksort($translations);
        }

        $flags = ($prettyPrint ? JSON_PRETTY_PRINT : 0)
            | ($unescapeUnicode ? JSON_UNESCAPED_UNICODE : 0);

        $content = json_encode($translations, $flags)."\n";

        $this->ensureDirectory(dirname($filePath));

        return file_put_contents($filePath, $content) !== false;
    }

    private function ensureDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
