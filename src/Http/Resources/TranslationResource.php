<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
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
        return [
            'id' => $this->id,
            'language' => LanguageResource::make($this->whenLoaded('language')),
            'source' => $this->source,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'progress' => $this->formatProgress(),
            'phrases_count' => $this->phrases_count,
        ];
    }

    private function formatProgress(): string
    {
        if ($this->progress > 0) {
            return "{$this->progress}%";
        }

        return '0%';
    }

    /**
     * Create an AnonymousResourceCollection without wrapping
     *
     * @see JsonResource::newCollection()
     *
     * @param  mixed|Collection  $resource
     * @return UnwrappedAnonymousResourceCollection
     */
    protected static function newCollection($resource)
    {
        return new UnwrappedAnonymousResourceCollection($resource, TranslationResource::class);
    }
}
