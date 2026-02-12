<?php

namespace Outhebox\Translations\Services\Exporter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\ExportCompleted;
use Outhebox\Translations\Models\ExportLog;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;

class TranslationExporter
{
    public function __construct(
        protected PhpFileWriter $phpWriter,
        protected JsonFileWriter $jsonWriter,
    ) {}

    public function export(array $options = []): ExportResult
    {
        $startTime = microtime(true);
        $result = new ExportResult;
        $langPath = config('translations.lang_path', lang_path());

        $languages = $this->resolveLanguages($options['locale'] ?? null);

        foreach ($languages as $language) {
            $result->localeCount++;
            $this->exportLanguage($language, $langPath, $result, $options['group'] ?? null);
        }

        $result->durationMs = (int) ((microtime(true) - $startTime) * 1000);

        $log = $this->createExportLog($result, $options);
        ExportCompleted::dispatch($log);

        return $result;
    }

    private function resolveLanguages(?string $locale): Collection
    {
        $query = Language::query()->active();

        if ($locale) {
            $query->where('code', $locale);
        }

        return $query->get();
    }

    private function createExportLog(ExportResult $result, array $options): ExportLog
    {
        return ExportLog::query()->create([
            'locale_count' => $result->localeCount,
            'file_count' => $result->fileCount,
            'key_count' => $result->keyCount,
            'duration_ms' => $result->durationMs,
            'triggered_by' => $options['triggered_by'] ?? null,
            'source' => $options['source'] ?? 'cli',
        ]);
    }

    private function exportLanguage(Language $language, string $langPath, ExportResult $result, ?string $groupFilter): void
    {
        $groupQuery = $this->buildGroupQuery($language, $groupFilter);

        $groupQuery->chunkById(100, function ($groups) use ($language, $langPath, $result) {
            foreach ($groups as $group) {
                $this->exportGroup($group, $language, $langPath, $result);
            }
        });
    }

    private function buildGroupQuery(Language $language, ?string $groupFilter): Builder
    {
        $requireApproval = config('translations.export.require_approval', false);

        $query = Group::query()->with(['translationKeys.translations' => function ($query) use ($language, $requireApproval) {
            $query->where('language_id', $language->id);

            if ($requireApproval) {
                $query->where('status', TranslationStatus::Approved);
            } else {
                $query->where('status', '!=', TranslationStatus::Untranslated);
            }
        }]);

        if ($groupFilter) {
            $query->where('name', $groupFilter);
        }

        return $query;
    }

    private function exportGroup(Group $group, Language $language, string $langPath, ExportResult $result): void
    {
        $translations = $this->collectTranslations($group);

        if (empty($translations)) {
            return;
        }

        $result->keyCount += count($translations);
        $sortKeys = config('translations.export.sort_keys', true);

        if ($group->isJson()) {
            $filePath = $langPath.'/'.$language->code.'.json';
            $this->jsonWriter->write($filePath, $translations, $sortKeys);
        } else {
            $basePath = $group->namespace
                ? $langPath.'/vendor/'.$group->namespace.'/'.$language->code
                : $langPath.'/'.$language->code;

            $this->phpWriter->write($basePath.'/'.$group->name.'.php', $translations, $sortKeys);
        }

        $result->fileCount++;
    }

    private function collectTranslations(Group $group): array
    {
        $translations = [];

        foreach ($group->translationKeys as $key) {
            $translation = $key->translations->first();

            if ($translation && $translation->value !== null) {
                $translations[$key->key] = $translation->value;
            }
        }

        return $translations;
    }
}
