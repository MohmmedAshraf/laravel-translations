<?php

namespace Outhebox\Translations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Outhebox\Translations\Models\ExportLog;

class ExportCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ExportLog $exportLog,
    ) {}
}
