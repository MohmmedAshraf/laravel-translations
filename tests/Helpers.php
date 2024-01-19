<?php

use Brick\VarExporter\VarExporter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function createDirectoryIfNotExits($path): void
{
    // check if $path is a filename or a directory
    if (Str::contains($path, '.')) {
        $path = dirname($path);
    }

    if (! File::exists($path)) {
        File::makeDirectory($path, 0755, true);
    }
}

function createPhpLanguageFile($path, array $content): void
{
    $path = lang_path($path);

    createDirectoryIfNotExits($path);

    File::put($path, "<?php\n\nreturn ".VarExporter::export($content, VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);
}

function createJsonLanguageFile($path, array $content): void
{
    $path = lang_path($path);

    createDirectoryIfNotExits($path);

    File::put($path, json_encode($content, JSON_PRETTY_PRINT));
}
