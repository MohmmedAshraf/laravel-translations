<?php

namespace Outhebox\TranslationsUI\Actions;

use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

class CopySourceKeyToTranslationsAction
{
    public static function execute(Phrase $sourceKey): void
    {
        Translation::where('source', false)->get()->each(function ($translation) use ($sourceKey) {
            $isRoot = TranslationFile::find($sourceKey->file->id)?->is_root;
            $locale = $translation->language()->first()?->code;
            $translation->phrases()->create([
                'value' => null,
                'uuid' => str()->uuid(),
                'key' => $sourceKey->key,
                'group' => ($isRoot ? $locale : $sourceKey->group),
                'phrase_id' => $sourceKey->id,
                'parameters' => $sourceKey->parameters,
                'translation_file_id' => ($isRoot ? TranslationFile::firstWhere('name', $locale)?->id : $sourceKey->file->id),
            ]);
        });
    }
}
