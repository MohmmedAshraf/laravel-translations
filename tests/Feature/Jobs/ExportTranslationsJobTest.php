<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Outhebox\Translations\Jobs\ExportTranslationsJob;
use Outhebox\Translations\Services\Exporter\ExportResult;
use Outhebox\Translations\Services\Exporter\TranslationExporter;

it('dispatches to the correct queue', function () {
    Queue::fake();
    config(['translations.queue.name' => 'custom-queue']);

    ExportTranslationsJob::dispatch();

    Queue::assertPushedOn('custom-queue', ExportTranslationsJob::class);
});

it('calls the exporter with correct options', function () {
    $result = new ExportResult(fileCount: 5);

    $exporter = Mockery::mock(TranslationExporter::class);
    $exporter->shouldReceive('export')
        ->once()
        ->with(Mockery::on(fn ($options) => $options['locale'] === 'fr'
            && $options['group'] === 'messages'
            && $options['triggered_by'] === 'admin@test.com'
            && $options['source'] === 'ui'
        ))
        ->andReturn($result);

    $job = new ExportTranslationsJob(locale: 'fr', group: 'messages', triggeredBy: 'admin@test.com');
    $job->handle($exporter);
});

it('updates cache status during export', function () {
    $result = new ExportResult(fileCount: 10);

    $exporter = Mockery::mock(TranslationExporter::class);
    $exporter->shouldReceive('export')->andReturn($result);

    $job = new ExportTranslationsJob;
    $job->handle($exporter);

    $status = Cache::get('translations.export.status');
    expect($status['running'])->toBeFalse()
        ->and($status['progress'])->toBe(100)
        ->and($status['message'])->toContain('10 files written');
});

it('defaults to null locale and group', function () {
    $result = new ExportResult;

    $exporter = Mockery::mock(TranslationExporter::class);
    $exporter->shouldReceive('export')
        ->once()
        ->with(Mockery::on(fn ($options) => $options['locale'] === null && $options['group'] === null))
        ->andReturn($result);

    $job = new ExportTranslationsJob;
    $job->handle($exporter);
});
