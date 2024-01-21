<?php

namespace Outhebox\TranslationsUI\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Outhebox\TranslationsUI\Traits\HasDatabaseConnection;
use Outhebox\TranslationsUI\Traits\HasUuid;

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
        'source', 'file',
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

    public function similarPhrases(): Collection
    {
        return $this->translation->phrases()->where('key', 'like', "%$this->key%")
            ->whereKeyNot($this->id)
            ->get();
    }
}
