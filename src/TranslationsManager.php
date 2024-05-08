<?php

namespace Outhebox\TranslationsUI;

use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Outhebox\TranslationsUI\Models\Translation;
use Symfony\Component\Finder\SplFileInfo;
use ZipArchive;

class TranslationsManager
{
    const DOMAIN = null;

    const PATH = 'translations';

    const LOCALE = 'en';

    const FALLBACK_LOCALE = 'en';

    const MIDDLEWARE = ['web'];

    const DB_CONNECTION = null;

    const INCLUDE_FILE_IN_KEY = false;

    const SOURCE_LANGUAGE = 'en';

    const EXCLUDE_FILES = [];

    const CONFIG = [
        'domain' => self::DOMAIN,
        'path' => self::PATH,
        'locale' => self::LOCALE,
        'middleware' => self::MIDDLEWARE,
        'database_connection' => self::DB_CONNECTION,
        'include_file_in_key' => self::INCLUDE_FILE_IN_KEY,
        'source_language' => self::SOURCE_LANGUAGE,
        'exclude_files' => self::EXCLUDE_FILES,
    ];

    protected Application $laravel;

    protected Filesystem $filesystem;

    protected array $config = [];

    public function __construct(Application $laravel, Filesystem $filesystem, string $locale)
    {
        $this->filesystem = $filesystem;
        $this->laravel = $laravel;
        $this->initializeConfig();
        $this->setLocale($locale);
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

        collect($this->filesystem->files(lang_path()))->each(function ($file) use ($locales) {
            if ($this->filesystem->extension($file) !== 'json') {
                return;
            }
            foreach ($this->getExcludeFiles() as $excludeFile) {
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

    public function getTranslations(string $locale = ''): array
    {
        if (blank($locale)) {
            $locale = $this->getSourceLanguage();
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
                foreach ($this->getExcludeFiles() as $excludeFile) {

                    /**
                     * <h1>File exclusion by wildcard</h1>
                     * <h3>$file is with language like <code>en/book/create.php</code> while $excludedFile contains only wildcards or path like <code>book/create.php</code></h3>
                     * <h3>So, we need to remove the language part from $file before comparing with $excludeFile</h3>
                     */
                    if (fnmatch($excludeFile, str_replace($locale.DIRECTORY_SEPARATOR, '', $file))) {
                        return false;
                    }
                    if (Str::contains(str_replace($locale.DIRECTORY_SEPARATOR, '', $file), $excludeFile)) {
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

            $zip = new ZipArchive();

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

    public function export(bool $download = false): void
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

    public function getDomain(): ?string
    {
        return $this->get('domain', self::DOMAIN);
    }

    public function setDomain(string $domain): void
    {
        $this->set('domain', $domain);
    }

    public function getPath(): string
    {
        return $this->get('path');
    }

    public function setPath(string $path): void
    {
        $this->set('path', $path);
    }

    public function getLocale(): string
    {
        return $this->get('locale', self::LOCALE);
    }

    public function setLocale(string $locale): void
    {
        if (Str::contains($locale, ['/', '\\'])) {
            throw new InvalidArgumentException('Invalid characters present in locale.');
        }

        $this->set('locale', $locale);
    }

    public function getFallback(): string
    {
        return self::FALLBACK_LOCALE;
    }

    public function getMiddleware(): array
    {
        return $this->get('middleware', self::MIDDLEWARE);
    }

    public function setMiddleware(array $middleware): void
    {
        $this->set('middleware', $middleware);
    }

    public function getConnection(): ?string
    {
        return $this->get('database_connection');
    }

    public function setConnection(string $connection): void
    {
        $this->set('database_connection', $connection);
    }

    public function getIncludeFileInKey(): bool
    {
        return $this->get('include_file_in_key');
    }

    public function setIncludeFileInKey(bool $bool): void
    {
        $this->set('include_file_in_key', $bool);
    }

    public function getSourceLanguage(): string
    {
        return $this->get('source_language', self::SOURCE_LANGUAGE);
    }

    public function setSourceLanguage(string $locale): void
    {
        $this->set('source_language', $locale);
    }

    public function getExcludeFiles(): array
    {
        return $this->get('exclude_files');
    }

    public function setExcludeFiles(array $files): void
    {
        $this->set('exclude_files', $files);
    }

    public function initializeConfig(): array
    {
        foreach ($this->laravel['config']['translations'] as $key => $value) {
            $this->set($key, $value);
        }

        return $this->config;
    }

    private function get(array|string $key, mixed $default = null): mixed
    {
        if (is_array($key)) {
            $config = [];
            foreach ($key as $k => $v) {
                if (is_numeric($k)) {
                    [$k, $v] = [$v, null];
                }
                $config[$k] = Arr::get($this->config, $k, $v);
            }

            return $config;
        }

        return Arr::get($this->config, $key, $default);
    }

    private function set(array|string $key, mixed $value = null): void
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            if (keyNotExist($key, self::CONFIG)) {
                continue;
            }
            Arr::set($this->config, $key, $value);
        }
    }
}
