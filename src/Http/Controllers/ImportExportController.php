<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Outhebox\Translations\Jobs\ExportTranslationsJob;
use Outhebox\Translations\Jobs\ImportTranslationsJob;
use Outhebox\Translations\Services\Exporter\TranslationExporter;
use Outhebox\Translations\Services\Importer\TranslationImporter;

class ImportExportController extends Controller
{
    public function import(Request $request, TranslationImporter $importer): RedirectResponse
    {
        $fresh = $request->boolean('fresh');
        $overwrite = $request->boolean('overwrite', true);
        $triggeredBy = app('translations.auth')->id();

        if ($this->isQueueEnabled()) {
            ImportTranslationsJob::dispatch($fresh, $overwrite, $triggeredBy);

            return redirect()->back()->with('success', 'Import queued. Check status for progress.');
        }

        $result = $importer->import([
            'fresh' => $fresh,
            'overwrite' => $overwrite,
            'triggered_by' => $triggeredBy,
            'source' => 'ui',
        ]);

        return redirect()->back()->with('success', "Import completed: {$result->newCount} new keys, {$result->updatedCount} updated.");
    }

    public function export(Request $request, TranslationExporter $exporter): RedirectResponse
    {
        $locale = $request->input('locale');
        $group = $request->input('group');
        $triggeredBy = app('translations.auth')->id();

        if ($this->isQueueEnabled()) {
            ExportTranslationsJob::dispatch($locale, $group, $triggeredBy);

            return redirect()->back()->with('success', 'Export queued. Check status for progress.');
        }

        $result = $exporter->export([
            'locale' => $locale,
            'group' => $group,
            'triggered_by' => $triggeredBy,
            'source' => 'ui',
        ]);

        return redirect()->back()->with('success', "Export completed: {$result->fileCount} files written.");
    }

    public function importStatus(): JsonResponse
    {
        return $this->operationStatus('import');
    }

    public function exportStatus(): JsonResponse
    {
        return $this->operationStatus('export');
    }

    private function isQueueEnabled(): bool
    {
        return config('translations.queue.connection') !== null;
    }

    private function operationStatus(string $operation): JsonResponse
    {
        $status = Cache::get("translations.{$operation}.status", [
            'running' => false,
            'progress' => 0,
            'message' => "No {$operation} in progress.",
        ]);

        return response()->json($status);
    }
}
