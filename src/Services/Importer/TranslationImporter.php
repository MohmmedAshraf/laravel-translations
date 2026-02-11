<?php

namespace Outhebox\Translations\Services\Importer;

use Illuminate\Support\Facades\DB;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\ImportCompleted;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\ImportLog;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\KeyReplicator;
use Outhebox\Translations\Support\LanguageDataProvider;

class TranslationImporter
{
    public function __construct(
        protected PhpFileReader $phpReader,
        protected JsonFileReader $jsonReader,
        protected ParameterExtractor $parameterExtractor,
        protected KeyReplicator $keyReplicator,
    ) {}

    public function import(array $options = []): ImportResult
    {
        $overwrite = $options['overwrite'] ?? true;

        $startTime = microtime(true);
        $result = new ImportResult;
        $langPath = config('translations.lang_path', lang_path());
        $excludeFiles = config('translations.exclude_files', []);

        if ($options['fresh'] ?? false) {
            $this->clearAllTranslations();
        }

        $locales = $this->importPhpLocales($langPath, $excludeFiles, $result, $overwrite);
        $this->importJsonLocales($langPath, $locales, $result, $overwrite);
        $this->importVendorFiles($langPath, $excludeFiles, $result, $overwrite);

        $this->keyReplicator->replicateAll();

        $result->durationMs = (int) ((microtime(true) - $startTime) * 1000);

        $log = $this->createImportLog($result, $options);
        ImportCompleted::dispatch($log);

        return $result;
    }

    private function clearAllTranslations(): void
    {
        DB::transaction(function (): void {
            Translation::query()->delete();
            TranslationKey::query()->delete();
            Group::query()->delete();
        });
    }

    private function importPhpLocales(string $langPath, array $excludeFiles, ImportResult $result, bool $overwrite): array
    {
        $locales = $this->phpReader->discoverLocales($langPath);

        foreach ($locales as $locale) {
            $result->localeCount++;
            $language = $this->ensureLanguage($locale);
            $files = $this->phpReader->discoverFiles($langPath, $locale);

            foreach ($files as $groupName => $filePath) {
                if (in_array($groupName.'.php', $excludeFiles)) {
                    continue;
                }

                $this->importPhpGroup($language, $groupName, null, $filePath, $result, $overwrite, $langPath);
            }
        }

        return $locales;
    }

    private function importJsonLocales(string $langPath, array $phpLocales, ImportResult $result, bool $overwrite): void
    {
        $jsonFiles = $this->jsonReader->discoverFiles($langPath);

        foreach ($jsonFiles as $locale => $filePath) {
            if (! in_array($locale, $phpLocales)) {
                $result->localeCount++;
            }

            $language = $this->ensureLanguage($locale);
            $this->importJsonFile($language, $filePath, $result, $overwrite);
        }
    }

    private function importVendorFiles(string $langPath, array $excludeFiles, ImportResult $result, bool $overwrite): void
    {
        if (! config('translations.import.scan_vendor', true)) {
            return;
        }

        $vendorFiles = $this->phpReader->discoverVendorFiles($langPath);

        foreach ($vendorFiles as $namespace => $vendorLocales) {
            foreach ($vendorLocales as $locale => $files) {
                $language = $this->ensureLanguage($locale);

                foreach ($files as $groupName => $filePath) {
                    if (in_array($groupName.'.php', $excludeFiles)) {
                        continue;
                    }

                    $this->importPhpGroup($language, $groupName, $namespace, $filePath, $result, $overwrite, $langPath);
                }
            }
        }
    }

    private function createImportLog(ImportResult $result, array $options): ImportLog
    {
        return ImportLog::query()->create([
            'locale_count' => $result->localeCount,
            'key_count' => $result->keyCount,
            'new_count' => $result->newCount,
            'updated_count' => $result->updatedCount,
            'duration_ms' => $result->durationMs,
            'triggered_by' => $options['triggered_by'] ?? null,
            'source' => $options['source'] ?? 'cli',
            'fresh' => $options['fresh'] ?? false,
        ]);
    }

    private function importPhpGroup(Language $language, string $groupName, ?string $namespace, string $filePath, ImportResult $result, bool $overwrite, ?string $basePath = null): void
    {
        $group = Group::query()->firstOrCreate(
            ['name' => $groupName, 'namespace' => $namespace],
            ['file_format' => 'php', 'file_path' => $filePath],
        );

        $this->importGroupTranslations($group, $this->phpReader->read($filePath, $basePath), $language, $result, $overwrite);
    }

    private function importJsonFile(Language $language, string $filePath, ImportResult $result, bool $overwrite): void
    {
        $group = Group::query()->firstOrCreate(
            ['name' => '_json', 'namespace' => null],
            ['file_format' => 'json', 'file_path' => $filePath],
        );

        $this->importGroupTranslations($group, $this->jsonReader->read($filePath), $language, $result, $overwrite);
    }

    private function importGroupTranslations(Group $group, array $translations, Language $language, ImportResult $result, bool $overwrite): void
    {
        $result->keyCount += count($translations);

        foreach ($translations as $key => $value) {
            if (! is_string($value)) {
                continue;
            }

            $this->importTranslation($group, (string) $key, $value, $language, $result, $overwrite);
        }
    }

    private function importTranslation(Group $group, string $key, string $value, Language $language, ImportResult $result, bool $overwrite): void
    {
        $metadata = $this->extractMetadata($value);

        $translationKey = TranslationKey::query()->firstOrCreate(
            ['group_id' => $group->id, 'key' => $key],
            $metadata,
        );

        if ($translationKey->wasRecentlyCreated) {
            $result->newCount++;
        } elseif ($language->is_source && $value) {
            $translationKey->update($metadata);
        }

        $this->upsertTranslationValue($translationKey, $language, $value, $overwrite, $result);
    }

    private function extractMetadata(string $value): array
    {
        $parameters = config('translations.import.detect_parameters', true)
            ? $this->parameterExtractor->extract($value)
            : [];

        return [
            'parameters' => $parameters ?: null,
            'is_html' => config('translations.import.detect_html', true) && $this->parameterExtractor->containsHtml($value),
            'is_plural' => config('translations.import.detect_plural', true) && $this->parameterExtractor->isPlural($value),
        ];
    }

    private function upsertTranslationValue(TranslationKey $translationKey, Language $language, string $value, bool $overwrite, ImportResult $result): void
    {
        $translation = Translation::query()
            ->where('translation_key_id', $translationKey->id)
            ->where('language_id', $language->id)
            ->first();

        if (! $translation) {
            (new Translation([
                'translation_key_id' => $translationKey->id,
                'language_id' => $language->id,
                'value' => $value,
                'status' => TranslationStatus::Translated,
            ]))->saveQuietly();

            return;
        }

        if ($overwrite && $translation->value !== $value) {
            $translation->fill([
                'value' => $value,
                'status' => TranslationStatus::Translated,
            ])->saveQuietly();
            $result->updatedCount++;
        }
    }

    private function ensureLanguage(string $code): Language
    {
        $language = Language::query()->where('code', $code)->first();

        if ($language) {
            if (! $language->active) {
                $language->update(['active' => true]);
            }

            return $language;
        }

        $knownLanguage = LanguageDataProvider::findByCode($code);

        return Language::query()->create([
            'code' => $code,
            'name' => $knownLanguage['name'] ?? $code,
            'native_name' => $knownLanguage['native_name'] ?? $code,
            'rtl' => $knownLanguage['rtl'] ?? false,
            'active' => true,
            'is_source' => $code === config('translations.source_language', 'en'),
        ]);
    }
}
