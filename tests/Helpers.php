<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Brick\VarExporter\VarExporter;

function createDirectoryIfNotExits($path)
{
    // check if $path is a filename or a directory
    if (Str::contains($path, '.')) {
        $path = dirname($path);
    }

    if (! File::exists($path)) {
        File::makeDirectory($path, 0755, true);
    }
}

function createPhpLanguageFile($path, array $content)
{
    createDirectoryIfNotExits($path);

    File::put($path, "<?php\n\nreturn " . VarExporter::export($content, VarExporter::TRAILING_COMMA_IN_ARRAY) . ';' . PHP_EOL);
}

function createJsonLangaueFile($path, array $content)
{
    createDirectoryIfNotExits($path);

    File::put($path, json_encode($content, JSON_PRETTY_PRINT));
}
