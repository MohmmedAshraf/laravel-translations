<?php

namespace Outhebox\TranslationsUI\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Outhebox\TranslationsUI\Traits\HasDatabaseConnection;

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
