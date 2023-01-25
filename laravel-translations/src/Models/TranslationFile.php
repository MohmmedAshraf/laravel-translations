<?php

namespace Outhebox\LaravelTranslations\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Outhebox\LaravelTranslations\Models\Concerns\HasDatabaseConnection;

class TranslationFile extends Model
{
    use HasFactory;
    use HasDatabaseConnection;

    protected $guarded = [];

    protected $table = 'ltu_translation_files';

    public $timestamps = false;

    public function phrases(): HasMany
    {
        return $this->hasMany(Phrase::class);
    }

    public function fileName(): Attribute
    {
        return Attribute::get(function () {
            return "$this->name.$this->extension";
        });
    }
}
