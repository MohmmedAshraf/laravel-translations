<?php

namespace Outhebox\TranslationsUI\Actions;

use Outhebox\TranslationsUI\Models\Translation;

class CopyPhrasesFromSourceAction
{
    public static function execute(Translation $translation): void
    {
        $sourceTranslation = Translation::where('source', true)->first();

        $sourceTranslation->phrases()->with('file')->get()->each(function ($sourcePhrase) use ($translation) {
            $translation->phrases()->create([
                'value' => null,
                'uuid' => str()->uuid(),
                'key' => $sourcePhrase->key,
                'group' => $sourcePhrase->group,
                'phrase_id' => $sourcePhrase->id,
                'parameters' => $sourcePhrase->parameters,
                'translation_file_id' => $sourcePhrase->file->id,
            ]);
        });
    }
}
