<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Outhebox\Translations\Database\Factories\TranslationKeyFactory;
use Outhebox\Translations\Enums\TranslationStatus;

class TranslationKey extends Model
{
    use HasFactory;

    protected $table = 'ltu_translation_keys';

    protected $fillable = [
        'group_id',
        'key',
        'context_note',
        'parameters',
        'is_html',
        'is_plural',
        'priority',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'is_html' => 'boolean',
            'is_plural' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function hasParameters(): bool
    {
        return ! empty($this->parameters);
    }

    public function parameterNames(): array
    {
        return $this->parameters ?? [];
    }

    public function scopeInGroup(Builder $query, int $groupId): Builder
    {
        return $query->where('group_id', $groupId);
    }

    public function scopeWithMissingTranslations(Builder $query, int $languageId): Builder
    {
        return $query->whereDoesntHave('translations', function (Builder $q) use ($languageId) {
            $q->where('language_id', $languageId)
                ->where('status', '!=', TranslationStatus::Untranslated);
        });
    }

    protected static function newFactory(): TranslationKeyFactory
    {
        return TranslationKeyFactory::new();
    }
}
