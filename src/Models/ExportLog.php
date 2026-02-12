<?php

namespace Outhebox\Translations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outhebox\Translations\Database\Factories\ExportLogFactory;

class ExportLog extends Model
{
    use HasFactory;

    protected $table = 'ltu_export_logs';

    protected $fillable = [
        'locale_count',
        'file_count',
        'key_count',
        'duration_ms',
        'triggered_by',
        'source',
        'notes',
    ];

    public function getConnectionName(): ?string
    {
        return config('translations.database_connection') ?? parent::getConnectionName();
    }

    protected static function newFactory(): ExportLogFactory
    {
        return ExportLogFactory::new();
    }
}
