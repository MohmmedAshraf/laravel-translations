<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\TranslationsUI\Models\Translation;

/**
 * @mixin Translation
 *
 * @property mixed $progress
 */
class TranslationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'phrases_count' => $this->phrases_count,
            'language' => LanguageResource::make($this->whenLoaded('language')),
            'progress' => $this->progress > 0 ? number_format($this->progress, 2) : 0,
        ];
    }
}
