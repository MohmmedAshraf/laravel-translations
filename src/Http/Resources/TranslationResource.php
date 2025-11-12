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
    public static $wrap = null;

    public function toArray($request): array
    {
        $progress = null;
        if (isset($this->progress)) {
            $progress = $this->progress > 0 ? number_format($this->progress, 2) : 0;
        }

        return [
            'id' => $this->id,
            'source' => $this->source,
            'phrases_count' => $this->phrases_count ?? 0,
            'language' => LanguageResource::make($this->whenLoaded('language')),
            'progress' => $progress,
        ];
    }

    protected static function newCollection($resource)
    {
        return new UnwrappedAnonymousResourceCollection($resource, TranslationResource::class);
    }
}
