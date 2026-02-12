<?php

namespace Outhebox\Translations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Outhebox\Translations\Services\Exporter\TranslationExporter;

class ExportTranslationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ?string $locale = null,
        public ?string $group = null,
        public ?string $triggeredBy = null,
    ) {
        $this->onConnection(config('translations.queue.connection'));
        $this->onQueue(config('translations.queue.name', 'translations'));
    }

    public function handle(TranslationExporter $exporter): void
    {
        Cache::put('translations.export.status', [
            'running' => true,
            'progress' => 0,
            'message' => 'Export started...',
        ], 3600);

        $result = $exporter->export([
            'locale' => $this->locale,
            'group' => $this->group,
            'triggered_by' => $this->triggeredBy,
            'source' => 'ui',
        ]);

        Cache::put('translations.export.status', [
            'running' => false,
            'progress' => 100,
            'message' => "Export completed: {$result->fileCount} files written.",
        ], 3600);
    }
}
