<?php

namespace Outhebox\TranslationsUI\Traits;

use Outhebox\TranslationsUI\Facades\TranslationsUI;

trait HasDatabaseConnection
{
    public function getConnectionName(): ?string
    {
        if ($connection = TranslationsUI::getConnection()) {
            return $connection;
        }

        return parent::getConnectionName();
    }
}
