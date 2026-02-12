<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outhebox\Translations\Database\Factories\ImportLogFactory;

class ImportLog extends Model
{
    use HasFactory;

    protected $table = 'ltu_import_logs';

    protected $fillable = [
        'locale_count',
        'key_count',
        'new_count',
        'updated_count',
        'duration_ms',
        'triggered_by',
        'source',
        'fresh',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'fresh' => 'boolean',
        ];
    }

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    protected static function newFactory(): ImportLogFactory
    {
        return ImportLogFactory::new();
    }
}
