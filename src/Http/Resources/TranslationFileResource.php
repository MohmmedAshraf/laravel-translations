<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\TranslationsUI\Http\Resources\Collections\PhraseCollection;
use Outhebox\TranslationsUI\Models\TranslationFile;

/** @mixin TranslationFile */
class TranslationFileResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'extension' => $this->extension,
            'nameWithExtension' => "$this->name.$this->extension",
            'phrases' => new PhraseCollection($this->whenLoaded('phrases')),
        ];
    }
}
