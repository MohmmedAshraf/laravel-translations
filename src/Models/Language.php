<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Outhebox\Translations\Database\Factories\LanguageFactory;

class Language extends Model
{
    use HasFactory;

    protected $table = 'ltu_languages';

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'rtl',
        'is_source',
        'tone',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'rtl' => 'boolean',
            'is_source' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function contributors(): BelongsToMany
    {
        return $this->belongsToMany(Contributor::class, 'ltu_contributor_language')
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeSource(Builder $query): Builder
    {
        return $query->where('is_source', true);
    }

    public function isSource(): bool
    {
        return $this->is_source;
    }

    public static function source(): ?static
    {
        return once(fn () => static::query()->source()->first());
    }

    protected static function newFactory(): LanguageFactory
    {
        return LanguageFactory::new();
    }
}
