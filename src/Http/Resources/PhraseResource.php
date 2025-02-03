<?php

namespace Outhebox\TranslationsUI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Outhebox\TranslationsUI\Models\Phrase;

/** @mixin Phrase */
class PhraseResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'key' => $this->key,
            'group' => $this->group,
            'value' => $this->value,
            'parameters' => $this->parameters,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'state' => (bool) $this->value,
            'note' => $this->note,
            'value_html' => $this->splitParameters(),
            'translation_id' => $this->translation_id,
            'translation_file_id' => $this->translation_file_id,
            'phrase_id' => $this->phrase_id,
            'file' => TranslationFileResource::make($this->whenLoaded('file')),
            'translation' => TranslationResource::make($this->whenLoaded('translation')),
            'source' => PhraseResource::make($this->whenLoaded('source')),
        ];
    }

    private function splitParameters(): array
    {
        if (blank($this->parameters)) {
            return [];
        }

        $result = collect();

        foreach (explode(' ', $this->value) as $word) {
            if (preg_match('/(?<!\w):(\w+)/', $word)) {
                $result->push([
                    'parameter' => true,
                    'value' => $word,
                ]);
            } else {
                $result->push([
                    'parameter' => false,
                    'value' => $word,
                ]);
            }
        }

        return $result->toArray();
    }
}
