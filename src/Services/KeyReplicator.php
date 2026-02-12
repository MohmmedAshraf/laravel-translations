<?php

namespace Outhebox\Translations\Services;

use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

class KeyReplicator
{
    public function replicateAll(): void
    {
        $languages = Language::query()->where('active', true)->pluck('id');

        foreach ($languages as $languageId) {
            TranslationKey::query()
                ->whereNotIn('id', function ($query) use ($languageId) {
                    $query->select('translation_key_id')
                        ->from('ltu_translations')
                        ->where('language_id', $languageId);
                })
                ->chunkById(500, function ($keys) use ($languageId) {
                    $records = $keys->map(fn ($key) => [
                        'translation_key_id' => $key->id,
                        'language_id' => $languageId,
                        'value' => null,
                        'status' => TranslationStatus::Untranslated->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();

                    Translation::query()->upsert(
                        $records,
                        ['translation_key_id', 'language_id'],
                        ['updated_at'],
                    );
                });
        }
    }

    public function replicateKey(TranslationKey $key, ?string $sourceValue = null): void
    {
        $sourceLanguage = Language::query()->where('is_source', true)->first();
        $languages = Language::query()->where('active', true)->pluck('id');

        foreach ($languages as $languageId) {
            $isSource = $languageId === $sourceLanguage?->id;

            Translation::query()->firstOrCreate(
                ['translation_key_id' => $key->id, 'language_id' => $languageId],
                [
                    'value' => $isSource ? $sourceValue : null,
                    'status' => ($isSource && $sourceValue)
                        ? TranslationStatus::Translated->value
                        : TranslationStatus::Untranslated->value,
                ]
            );
        }
    }

    public function replicateForLanguage(Language $language): void
    {
        TranslationKey::query()->chunkById(500, function ($keys) use ($language) {
            $records = $keys->map(fn ($key) => [
                'translation_key_id' => $key->id,
                'language_id' => $language->id,
                'value' => null,
                'status' => TranslationStatus::Untranslated->value,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            Translation::query()->upsert(
                $records,
                ['translation_key_id', 'language_id'],
                ['updated_at'],
            );
        });
    }
}
