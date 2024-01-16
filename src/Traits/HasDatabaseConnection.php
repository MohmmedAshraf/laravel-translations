<?php

namespace Outhebox\TranslationsUI\Traits;

trait HasDatabaseConnection
{
    public function getConnectionName()
    {
        if ($connection = config('translations.database_connection')) {
            return $connection;
        }

        return parent::getConnectionName();
    }
}
