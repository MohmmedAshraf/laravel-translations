<?php

namespace Outhebox\LaravelTranslations\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\LaravelTranslations\Models\Translation;

/** @mixin Translation */
class TranslationResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'language' => LanguageResource::make($this->whenLoaded('language')),
            'source' => $this->source,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'progress' => $this->progress,
            'phrases_count' => $this->phrases_count,
        ];
    }
}
