<?php

namespace Outhebox\LaravelTranslations\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\LaravelTranslations\Models\Language;

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
}
