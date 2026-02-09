<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Outhebox\Translations\Database\Factories\TranslationFactory;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\TranslationSaved;

class Translation extends Model
{
    use HasFactory;

    protected $table = 'ltu_translations';

    protected static array $revisionContext = [];

    protected static array $pendingOldValues = [];

    protected $fillable = [
        'translation_key_id',
        'language_id',
        'value',
        'status',
        'needs_review',
        'translated_by',
        'reviewed_by',
        'reviewer_feedback',
        'ai_generated',
        'ai_provider',
    ];

    protected function casts(): array
    {
        return [
            'status' => TranslationStatus::class,
            'needs_review' => 'boolean',
            'ai_generated' => 'boolean',
        ];
    }

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    public function translationKey(): BelongsTo
    {
        return $this->belongsTo(TranslationKey::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeTranslated(Builder $query): Builder
    {
        return $query->where('status', TranslationStatus::Translated);
    }

    public function scopeUntranslated(Builder $query): Builder
    {
        return $query->where('status', TranslationStatus::Untranslated);
    }

    public function scopeNeedsReview(Builder $query): Builder
    {
        return $query->where('status', TranslationStatus::NeedsReview);
    }

    public static function withRevisionContext(mixed $changeType, ?string $changedBy = null, ?array $metadata = null): void
    {
        static::$revisionContext = [
            'changeType' => $changeType,
            'changedBy' => $changedBy,
            'metadata' => $metadata,
        ];
    }

    public static function clearRevisionContext(): void
    {
        static::$revisionContext = [];
    }

    public static function resetStaticState(): void
    {
        static::$revisionContext = [];
        static::$pendingOldValues = [];
    }

    protected static function booted(): void
    {
        static::saving(function (Translation $translation): void {
            static::captureOldValue($translation);
        });

        static::saved(function (Translation $translation): void {
            static::handleSaved($translation);
        });
    }

    protected static function captureOldValue(Translation $translation): void
    {
        if (count(static::$pendingOldValues) > 1000) {
            static::$pendingOldValues = [];
        }

        if ($translation->isDirty('value')) {
            $key = $translation->id ?? spl_object_id($translation);
            static::$pendingOldValues[$key] = $translation->getOriginal('value');
        }
    }

    protected static function handleSaved(Translation $translation): void
    {
        $key = $translation->id ?? spl_object_id($translation);
        $oldValue = static::$pendingOldValues[$key] ?? null;
        unset(static::$pendingOldValues[$key]);

        if ($translation->wasRecentlyCreated) {
            unset(static::$pendingOldValues[spl_object_id($translation)]);
        }

        $valueChanged = $translation->wasChanged('value')
            || ($translation->wasRecentlyCreated && $translation->value !== null);

        if (! $valueChanged) {
            static::clearRevisionContext();

            return;
        }

        TranslationSaved::dispatch(
            $translation,
            $oldValue,
            static::$revisionContext['changeType'] ?? null,
            static::$revisionContext['changedBy'] ?? null,
            static::$revisionContext['metadata'] ?? null,
        );

        static::clearRevisionContext();
    }

    protected static function newFactory(): TranslationFactory
    {
        return TranslationFactory::new();
    }
}
