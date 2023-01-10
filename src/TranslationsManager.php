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

        foreach ($this->filesystem->directories(lang_path()) as $dir) {
            if (Str::contains($dir, 'vendor')) {
                continue;
            }

            $locales->push(basename($dir));
        }

        return $locales->toArray();
    }

    public function getTranslations(string $local): array
    {
        if (blank($local)) {
            $local = config('translations.source_language');
        }

        collect($this->filesystem->allFiles(lang_path($local)))
            ->filter(function ($file) {
                return ! in_array($file->getFilename(), config('translations.exclude_files'));
            })
            ->filter(function ($file) {
                return $this->filesystem->extension($file) == 'php' || $this->filesystem->extension($file) == 'json';
            })
            ->each(function ($file) {
                try {
                    if ($this->filesystem->extension($file) == 'php') {
                        $this->translations[$file->getFilename()] = $this->filesystem->getRequire($file->getPathname());
                    }

                    if ($this->filesystem->extension($file) == 'json') {
                        $this->translations[$file->getFilename()] = json_decode($this->filesystem->get($file), true);
                    }
                } catch (FileNotFoundException $e) {
                    $this->translations[$file->getFilename()] = [];
                }
            });

        return $this->translations;
    }

    public function export(): void
    {
        $translations = Translation::with('phrases')->get();

        foreach ($translations as $translation) {
            $phrasesTree = $this->buildPhrasesTree($translation->phrases()->with('file')->whereNotNull('value')->get(), $translation->language->code);

            foreach ($phrasesTree as $locale => $groups) {
                foreach ($groups as $file => $phrases) {
                    $path = lang_path("$locale/$file");

                    if (! $this->filesystem->isDirectory(dirname($path))) {
                        $this->filesystem->makeDirectory(dirname($path), 0755, true);
                    }

                    if (! $this->filesystem->exists($path)) {
                        $this->filesystem->put($path, "<?php\n\nreturn [\n\n]; ".PHP_EOL);
                    }

                    if ($this->filesystem->extension($path) == 'php') {
                        try {
                            $this->filesystem->put($path, "<?php\n\nreturn ".VarExporter::export($phrases, VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);
                        } catch (ExportException $e) {
                            logger()->error($e->getMessage());
                        }
                    }

                    if ($this->filesystem->extension($path) == 'json') {
                        $this->filesystem->put($path, json_encode($phrases, JSON_PRETTY_PRINT));
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
