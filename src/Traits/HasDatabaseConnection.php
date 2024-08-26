<?php

namespace Outhebox\TranslationsUI\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HasDatabaseConnection
{
    public function getDatabaseConnection(): ?string
    {
        if ($connection = config('translations.database_connection')) {
            return $connection;
        }

        return null;
    }

    public function getConnectionName(): ?string
    {
        return $this->getDatabaseConnection();
    }

    public function schema(): \Illuminate\Database\Schema\Builder
    {
        return Schema::connection($this->getDatabaseConnection());
    }

    public function db(): \Illuminate\Database\Connection
    {
        return DB::connection($this->getDatabaseConnection());
    }
}
