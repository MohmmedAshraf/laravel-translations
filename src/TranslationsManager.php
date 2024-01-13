<?php

namespace Outhebox\LaravelTranslations;

use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Outhebox\LaravelTranslations\Models\Translation;

class TranslationsManager
{
    private array $translations = [];

    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getLocales(): array
    {
        $locales = collect();

        // get locales from direcotries
        foreach ($this->filesystem->directories(lang_path()) as $dir) {
            if (Str::contains($dir, 'vendor')) {
                continue;
            }

            $locales->push(basename($dir));
        }

        // get locales from json files in the root of the lang directory
        collect($this->filesystem->files(lang_path()))->each(function ($file) use ($locales) {
            // if (Str::contains($file->getFilename(), 'vendor')) {
            //     return;
            // }

            if ($this->filesystem->extension($file) != 'json') {
                return;
            }

            if (! $locales->contains($file->getFilenameWithoutExtension())) {
                $locales->push($file->getFilenameWithoutExtension());
            }
        });

        return $locales->toArray();
    }

    public function getTranslations(string $locale): array
    {
        if (blank($locale)) {
            $locale = config('translations.source_language');
        }

        $translations = [];
        $baseFileName = "{$locale}.json";

        collect($this->filesystem->allFiles(lang_path($locale)))
            ->map(function ($file) use ($locale) {
                return $locale . DIRECTORY_SEPARATOR . $file->getFilename();
            })
            ->when($this->filesystem->exists(lang_path($baseFileName)), function ($collection) use ($baseFileName) {
                return $collection->prepend($baseFileName);
            })
            ->filter(function ($file) {
                foreach (config('translations.exclude_files') as $excludeFile) {
                    if (fnmatch($excludeFile, $file)) {
                        return false;
                    }
                    if (fnmatch($excludeFile, basename($file))) {
                        return false;
                    }

                    return true;
                }

                return ! in_array($file, config('translations.exclude_files'));
            })
            ->filter(function ($file) {
                return $this->filesystem->extension($file) == 'php' || $this->filesystem->extension($file) == 'json';
            })
            ->each(function ($file) use (&$translations) {
                try {
                    if ($this->filesystem->extension($file) == 'php') {
                        $translations[$file] = $this->filesystem->getRequire(lang_path($file));
                    }

                    if ($this->filesystem->extension($file) == 'json') {
                        $translations[$file] = json_decode($this->filesystem->get(lang_path($file)), true);
                    }
                } catch (FileNotFoundException $e) {
                    $translations[$file] = [];
                }
            });

        return $translations;
    }

    public function export(): void
    {
        $translations = Translation::with('phrases')->get();

        foreach ($translations as $translation) {
            $phrasesTree = $this->buildPhrasesTree($translation->phrases()->with('file')->whereNotNull('value')->get(), $translation->language->code);

            foreach ($phrasesTree as $locale => $groups) {
                foreach ($groups as $file => $phrases) {
                    $path = lang_path("{$file}");

                    if (! $this->filesystem->isDirectory(dirname($path))) {
                        $this->filesystem->makeDirectory(dirname($path), 0o755, true);
                    }

                    if (! $this->filesystem->exists($path)) {
                        $this->filesystem->put($path, "<?php\n\nreturn [\n\n]; " . PHP_EOL);
                    }

                    if ($this->filesystem->extension($path) == 'php') {
                        try {
                            $this->filesystem->put($path, "<?php\n\nreturn " . VarExporter::export($phrases, VarExporter::TRAILING_COMMA_IN_ARRAY) . ';' . PHP_EOL);
                        } catch (ExportException $e) {
                            logger()->error($e->getMessage());
                        }
                    }

                    if ($this->filesystem->extension($path) == 'json') {
                        $this->filesystem->put($path, json_encode($phrases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    }
                }
            }
        }
    }

    protected function buildPhrasesTree($phrases, $locale): array
    {
        $tree = [];

        foreach ($phrases as $phrase) {
            Arr::set($tree[$locale][$phrase->file->file_name], $phrase->key, $phrase->value);
        }

        return $tree;
    }

    public function getPhraseParameters(string $phrase): ?array
    {
        preg_match_all('/(?<!\w):(\w+)/', $phrase, $matches);

        if (empty($matches[1])) {
            return null;
        }

        return $matches[1];
    }
}
