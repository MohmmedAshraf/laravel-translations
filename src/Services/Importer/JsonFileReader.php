<?php

namespace Outhebox\Translations\Services\Importer;

use DirectoryIterator;

class JsonFileReader
{
    public function read(string $filePath): array
    {
        if (! file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);

        if ($content === false) {
            return [];
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    public function discoverFiles(string $langPath): array
    {
        if (! is_dir($langPath)) {
            return [];
        }

        $files = [];

        foreach (new DirectoryIterator($langPath) as $item) {
            if ($item->isDot() || ! $item->isFile() || $item->getExtension() !== 'json') {
                continue;
            }

            $locale = $item->getBasename('.json');
            $files[$locale] = $item->getPathname();
        }

        ksort($files);

        return $files;
    }
}
