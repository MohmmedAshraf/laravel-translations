<?php

namespace Outhebox\TranslationsUI;

use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Outhebox\TranslationsUI\Models\Translation;
use Symfony\Component\Finder\SplFileInfo;
use ZipArchive;

class TranslationsManager
{
    public function __construct(
        protected Filesystem $filesystem
    ) {}

    public function getLocales(): array
    {
        $locales = collect();

        foreach ($this->filesystem->directories(lang_path()) as $dir) {
            if (Str::contains($dir, 'vendor')) {
                continue;
            }

            $locales->push(basename($dir));
        }

        collect($this->filesystem->files(lang_path()))->each(function ($file) use ($locales) {
            if ($this->filesystem->extension($file) !== 'json') {
                return;
            }
            foreach (config('translations.exclude_files') as $excludeFile) {
                if (fnmatch($excludeFile, $file)) {
                    return;
                }
                if (fnmatch($excludeFile, basename($file))) {
                    return;
                }
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
        $rootFileName = "$locale.json";

        $files = [];

        if ($this->filesystem->exists(lang_path($locale))) {
            $files = $this->filesystem->allFiles(lang_path($locale));
        }

        collect($files)
            ->map(function (SplFileInfo $file) use ($locale) {
                if ($file->getRelativePath() === '') {
                    return $locale.DIRECTORY_SEPARATOR.$file->getFilename();
                }

                return $locale.DIRECTORY_SEPARATOR.$file->getRelativePath().DIRECTORY_SEPARATOR.$file->getFilename();
            })
            ->when($this->filesystem->exists(lang_path($rootFileName)), function ($collection) use ($rootFileName) {
                return $collection->prepend($rootFileName);
            })
            ->filter(function ($file) use ($locale) {
                foreach (Str::replace('/', DIRECTORY_SEPARATOR, config('translations.exclude_files')) as $excludeFile) {

                    /**
                     * <h1>File exclusion by wildcard</h1>
                     * <h3>$file is with language like <code>en/book/create.php</code> while $excludedFile contains only wildcards or path like <code>book/create.php</code></h3>
                     * <h3>So, we need to remove the language part from $file before comparing with $excludeFile</h3>
                     */
                    if (fnmatch($excludeFile, str_replace($locale.DIRECTORY_SEPARATOR, '', $file)) || Str::contains(str_replace($locale.DIRECTORY_SEPARATOR, '', $file), $excludeFile)) {
                        return false;
                    }
                }

                return true;
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

    public function download(): ?string
    {
        try {
            $this->export(download: true);

            $zip = new ZipArchive;

            $zipPath = storage_path('app/lang.zip');
            $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $baseDir = storage_path('app/translations');
            $zip->addEmptyDir('lang');

            $files = $this->filesystem->allFiles($baseDir);

            foreach ($files as $file) {
                $relativePath = str_replace($baseDir.DIRECTORY_SEPARATOR, '', $file->getPathname());
                $zip->addFile($file->getPathname(), 'lang/'.$relativePath);
            }

            $zip->close();

            return $zipPath;
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return null;
        }
    }

    public function export($download = false): void
    {
        $translations = Translation::with('phrases')->get();

        foreach ($translations as $translation) {
            $phrasesTree = buildPhrasesTree($translation->phrases()->with('file')->whereNotNull('value')->get(), $translation->language->code);

            foreach ($phrasesTree as $locale => $groups) {
                foreach ($groups as $file => $phrases) {
                    if ($file === "$locale.json") {
                        $langPath = $download ? storage_path("app/translations/$file") : lang_path("$file");
                    } else {
                        $langPath = $download ? storage_path("app/translations/$locale/$file") : lang_path("$locale/$file");
                    }

                    if (! $this->filesystem->isDirectory(dirname($langPath))) {
                        $this->filesystem->makeDirectory(dirname($langPath), 0755, true);
                    }

                    if (! $this->filesystem->exists($langPath)) {
                        $this->filesystem->put($langPath, "<?php\n\nreturn [\n\n]; ".PHP_EOL);
                    }

                    if ($this->filesystem->extension($langPath) == 'php') {
                        try {
                            $this->filesystem->put($langPath, "<?php\n\nreturn ".VarExporter::export($phrases, VarExporter::TRAILING_COMMA_IN_ARRAY).';'.PHP_EOL);
                        } catch (ExportException $e) {
                            logger()->error($e->getMessage());
                        }
                    }

                    if ($this->filesystem->extension($langPath) == 'json') {
                        $this->filesystem->put($langPath, json_encode($phrases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    }
                }
            }
        }
    }
}
