<?php

namespace Outhebox\TranslationsUI\Actions;

use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;

class CopySourceKeyToTranslationsAction
{
    public static function execute(Phrase $sourceKey): void
    {
        Translation::where('source', false)->get()->each(function ($translation) use ($sourceKey) {
            $translation->phrases()->create([
                'value' => null,
                'uuid' => str()->uuid(),
                'key' => $sourceKey->key,
                'group' => $sourceKey->group,
                'phrase_id' => $sourceKey->id,
                'parameters' => $sourceKey->parameters,
                'translation_file_id' => $sourceKey->file->id,
            ]);
        });
    }
}
