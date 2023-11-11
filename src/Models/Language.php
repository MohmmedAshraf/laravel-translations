<?php

namespace Outhebox\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Outhebox\LaravelTranslations\Models\Concerns\HasDatabaseConnection;

class Language extends Model
{
    use HasDatabaseConnection;
    use HasFactory;

    protected $guarded = [];

    protected $table = 'ltu_languages';

    public $timestamps = false;

    public function translation(): HasOne
    {
        return $this->hasOne(Translation::class);
    }

    public function phrases(): HasManyThrough
    {
        return $this->hasManyThrough(Phrase::class, Translation::class);
    }
}
