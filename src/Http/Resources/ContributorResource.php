<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Outhebox\TranslationsUI\Models\Contributor;

/** @mixin Contributor */
class ContributorResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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
        return new UnwrappedAnonymousResourceCollection($resource, ContributorResource::class);
    }
}
