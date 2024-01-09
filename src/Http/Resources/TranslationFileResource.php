<?php

namespace Outhebox\LaravelTranslations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\LaravelTranslations\Models\TranslationFile;

/** @mixin TranslationFile */
class TranslationFileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'extension' => $this->extension,
            'file_name' => $this->file_name,
            'phrases_count' => $this->phrases_count,

            'phrases' => PhraseResource::collection($this->whenLoaded('phrases')),
        ];
    }
}
