<?php

namespace Outhebox\Translations\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Outhebox\Translations\Models\Language;

trait HasTranslationRole
{
    public function getTranslationRole(): string
    {
        return $this->translation_role ?? 'translator';
    }

    public function getTranslationDisplayName(): string
    {
        return $this->name;
    }

    public function getTranslationEmail(): string
    {
        return $this->email;
    }

    public function getTranslationId(): string
    {
        return (string) $this->getKey();
    }

    public function assignedLanguages(): BelongsToMany
    {
        return $this->belongsToMany(
            Language::class,
            'ltu_contributor_language',
            'contributor_id',
            'language_id'
        );
    }
}
