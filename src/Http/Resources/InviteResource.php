<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Outhebox\TranslationsUI\Models\Invite;

/** @mixin Invite */
class InviteResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'token' => $this->token,
            'role' => [
                'value' => $this->role->value,
                'label' => $this->role->label(),
            ],
            'languages' => $this->languages,
            'invited_at' => $this->created_at->format('D, d M Y h:i A'),
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
        return new UnwrappedAnonymousResourceCollection($resource, InviteResource::class);
    }
}
