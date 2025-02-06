<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Outhebox\TranslationsUI\Models\Language;

/** @mixin Language */
class LanguageResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'rtl' => $this->rtl,
        ];
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
        return new UnwrappedAnonymousResourceCollection($resource, LanguageResource::class);
    }
}
