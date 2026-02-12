<?php

namespace Outhebox\Translations\Services\Scanner;

use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class FileWalker
{
    public function walk(array $paths, array $ignorePaths, array $extensions): Generator
    {
        foreach ($paths as $scanPath) {
            if (! is_dir($scanPath)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($scanPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if (! $file instanceof SplFileInfo || ! $file->isFile()) {
                    continue;
                }

                $absolutePath = $file->getRealPath();

                if ($this->isIgnoredPath($absolutePath, $ignorePaths)) {
                    continue;
                }

                if (! $this->matchesExtension($absolutePath, $extensions)) {
                    continue;
                }

                yield [
                    'absolutePath' => $absolutePath,
                    'relativePath' => $this->relativePath($absolutePath),
                ];
            }
        }
    }

    private function isIgnoredPath(string $filePath, array $ignorePaths): bool
    {
        foreach ($ignorePaths as $ignorePath) {
            if (str_contains($filePath, '/'.$ignorePath.'/') || str_contains($filePath, '\\'.$ignorePath.'\\')) {
                return true;
            }
        }

        return false;
    }

    private function matchesExtension(string $filePath, array $extensions): bool
    {
        if (empty($extensions)) {
            return true;
        }

        foreach ($extensions as $ext) {
            if (str_ends_with($filePath, '.'.$ext)) {
                return true;
            }
        }

        return false;
    }

    private function relativePath(string $absolutePath): string
    {
        $basePath = base_path().'/';

        if (str_starts_with($absolutePath, $basePath)) {
            return substr($absolutePath, strlen($basePath));
        }

        return $absolutePath;
    }
}
