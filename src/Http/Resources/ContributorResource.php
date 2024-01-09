<?php

namespace Outhebox\LaravelTranslations\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\LaravelTranslations\Models\Contributor;

/** @mixin Contributor */
class ContributorResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
