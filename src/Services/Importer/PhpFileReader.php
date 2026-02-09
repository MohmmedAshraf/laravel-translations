<?php

namespace Outhebox\Translations\Services\Importer;

use DirectoryIterator;
use Illuminate\Support\Arr;

class PhpFileReader
{
    public function read(string $filePath, ?string $basePath = null): array
    {
        if (! file_exists($filePath)) {
            return [];
        }

        $realPath = realpath($filePath);

        if ($realPath === false) {
            return [];
        }

        if (pathinfo($realPath, PATHINFO_EXTENSION) !== 'php') {
            return [];
        }

        $allowedBase = $basePath ? realpath($basePath) : realpath(lang_path());

        if ($allowedBase !== false && ! str_starts_with($realPath, $allowedBase.DIRECTORY_SEPARATOR)) {
            return [];
        }

        $content = require $realPath;

        if (! is_array($content)) {
            return [];
        }

        return Arr::dot($content);
    }

    public function discoverLocales(string $langPath): array
    {
        if (! is_dir($langPath)) {
            return [];
        }

        $locales = [];

        foreach (new DirectoryIterator($langPath) as $item) {
            if ($item->isDot() || ! $item->isDir() || $item->getFilename() === 'vendor') {
                continue;
            }

            $locales[] = $item->getFilename();
        }

        sort($locales);

        return $locales;
    }

    public function discoverFiles(string $langPath, string $locale): array
    {
        $localePath = $langPath.'/'.$locale;

        if (! is_dir($localePath)) {
            return [];
        }

        $files = [];

        foreach (new DirectoryIterator($localePath) as $item) {
            if ($item->isDot() || ! $item->isFile() || $item->getExtension() !== 'php') {
                continue;
            }

            $group = $item->getBasename('.php');
            $files[$group] = $item->getPathname();
        }

        ksort($files);

        return $files;
    }

    public function discoverVendorFiles(string $langPath): array
    {
        $vendorPath = $langPath.'/vendor';

        if (! is_dir($vendorPath)) {
            return [];
        }

        $result = [];

        foreach (new DirectoryIterator($vendorPath) as $namespace) {
            if ($namespace->isDot() || ! $namespace->isDir()) {
                continue;
            }

            $ns = $namespace->getFilename();

            foreach (new DirectoryIterator($namespace->getPathname()) as $locale) {
                if ($locale->isDot() || ! $locale->isDir()) {
                    continue;
                }

                $loc = $locale->getFilename();
                $files = $this->discoverFiles($namespace->getPathname(), $loc);

                if (! empty($files)) {
                    $result[$ns][$loc] = $files;
                }
            }
        }

        return $result;
    }
}
