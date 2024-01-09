<?php

namespace Outhebox\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Outhebox\LaravelTranslations\Models\Concerns\HasDatabaseConnection;

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

    public function progress(): Attribute
    {
        return Attribute::get(function () {
            $phrases = $this->phrases()->toBase()
                ->selectRaw('COUNT(CASE WHEN value IS NOT NULL THEN 1 END) AS translated')
                ->selectRaw('COUNT(CASE WHEN value IS NULL THEN 1 END) AS untranslated')
                ->selectRaw('COUNT(*) AS total')
                ->first();

            return round(($phrases->translated / $phrases->total) * 100, 2);
        });
    }
}
