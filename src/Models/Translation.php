<?php

namespace Outhebox\TranslationsUI\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Outhebox\TranslationsUI\Traits\HasDatabaseConnection;

class Translation extends Model
{
    use HasDatabaseConnection;
    use HasFactory;

    protected $guarded = [];

    protected $table = 'ltu_translations';

    protected $casts = [
        'source' => 'boolean',
    ];

    protected $with = [
        'language',
    ];

    public function phrases(): HasMany
    {
        return $this->hasMany(Phrase::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeIsSource($query): void
    {
        $query->where('source', true);
    }

    public function scopeWithProgress($query): void
    {
        $query->addSelect([
            'progress' => Phrase::selectRaw('AVG(CASE WHEN value IS NOT NULL THEN 1 ELSE 0 END) * 100')
                ->whereColumn('ltu_phrases.translation_id', 'ltu_translations.id')
                ->limit(1),
        ])->withCasts([
            'progress' => 'decimal:1',
        ]);
    }
}
