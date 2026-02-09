<?php

namespace Outhebox\Translations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\ImportLog;

class ImportCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ImportLog $importLog,
    ) {}
}
