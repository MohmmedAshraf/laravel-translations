<?php

namespace Outhebox\TranslationsUI\Actions;

use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

class CopyPhrasesFromSourceAction
{
    public static function execute(Translation $translation): void
    {
        $sourceTranslation = Translation::where('source', true)->first();

        $sourceTranslation->phrases()->with('file')->get()->each(function ($sourcePhrase) use ($translation) {
            $file = $sourcePhrase->file;

            if ($file->is_root) {
                $file = TranslationFile::firstOrCreate([
                    'is_root' => true,
                    'extension' => $file->extension,
                    'name' => $translation->language->code,
                ]);
            }

            $translation->phrases()->create([
                'value' => null,
                'uuid' => str()->uuid(),
                'key' => $sourcePhrase->key,
                'group' => $file->name,
                'phrase_id' => $sourcePhrase->id,
                'parameters' => $sourcePhrase->parameters,
                'translation_file_id' => $file->id,
            ]);
        });
    }
}
