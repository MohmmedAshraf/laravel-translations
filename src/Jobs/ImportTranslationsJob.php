<?php

namespace Outhebox\Translations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Outhebox\Translations\Services\Importer\TranslationImporter;

class ImportTranslationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public bool $fresh = false,
        public bool $overwrite = true,
        public ?string $triggeredBy = null,
    ) {
        $this->onConnection(config('translations.queue.connection'));
        $this->onQueue(config('translations.queue.name', 'translations'));
    }

    public function handle(TranslationImporter $importer): void
    {
        Cache::put('translations.import.status', [
            'running' => true,
            'progress' => 0,
            'message' => 'Import started...',
        ], 3600);

        $result = $importer->import([
            'fresh' => $this->fresh,
            'overwrite' => $this->overwrite,
            'triggered_by' => $this->triggeredBy,
            'source' => 'ui',
        ]);

        Cache::put('translations.import.status', [
            'running' => false,
            'progress' => 100,
            'message' => "Import completed: {$result->newCount} new keys, {$result->updatedCount} updated.",
        ], 3600);
    }
}
