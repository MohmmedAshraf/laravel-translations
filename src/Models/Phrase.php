<?php

namespace Outhebox\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Outhebox\LaravelTranslations\Models\Concerns\HasDatabaseConnection;
use Outhebox\LaravelTranslations\Models\Concerns\HasUuid;

class Phrase extends Model
{
    use HasDatabaseConnection;
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    protected $table = 'ltu_phrases';

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $with = [
        'source',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(TranslationFile::class, 'translation_file_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Phrase::class, 'phrase_id');
    }

    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class);
    }
}
