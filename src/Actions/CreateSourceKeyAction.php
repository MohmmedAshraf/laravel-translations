<?php

namespace Outhebox\TranslationsUI\Actions;

use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

class CreateSourceKeyAction
{
    public static function execute(string $key, string $file, string $key_translation): void
    {
        $sourceTranslation = Translation::where('source', true)->first();

        $sourceKey = $sourceTranslation->phrases()->create([
            'key' => $key,
            'phrase_id' => null,
            'parameters' => null,
            'value' => $key_translation,
            'translation_file_id' => $file,
            'uuid' => str()->uuid(),
            'group' => TranslationFile::find($file)?->name,
        ]);

        CopySourceKeyToTranslationsAction::execute($sourceKey);
    }
}
