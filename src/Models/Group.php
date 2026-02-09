<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Outhebox\Translations\Database\Factories\GroupFactory;

class Group extends Model
{
    use HasFactory;

    protected $table = 'ltu_groups';

    protected $fillable = [
        'name',
        'namespace',
        'file_path',
        'file_format',
    ];

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    public function translationKeys(): HasMany
    {
        return $this->hasMany(TranslationKey::class);
    }

    public function isJson(): bool
    {
        return $this->file_format === 'json';
    }

    public function displayName(): string
    {
        if ($this->namespace) {
            return $this->namespace.'::'.$this->name;
        }

        return $this->name;
    }

    protected static function newFactory(): GroupFactory
    {
        return GroupFactory::new();
    }
}
