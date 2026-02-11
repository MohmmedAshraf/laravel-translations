<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Outhebox\Translations\Jobs\ImportTranslationsJob;
use Outhebox\Translations\Services\Importer\ImportResult;
use Outhebox\Translations\Services\Importer\TranslationImporter;

it('dispatches to the correct queue', function () {
    Queue::fake();
    config(['translations.queue.name' => 'custom-queue']);

    ImportTranslationsJob::dispatch();

    Queue::assertPushedOn('custom-queue', ImportTranslationsJob::class);
});

it('calls the importer with correct options', function () {
    $result = new ImportResult(newCount: 5, updatedCount: 3);

    $importer = Mockery::mock(TranslationImporter::class);
    $importer->shouldReceive('import')
        ->once()
        ->with(Mockery::on(fn ($options) => $options['fresh'] === true
            && $options['overwrite'] === false
            && $options['triggered_by'] === 'admin@test.com'
            && $options['source'] === 'ui'
        ))
        ->andReturn($result);

    app()->instance(TranslationImporter::class, $importer);

    $job = new ImportTranslationsJob(fresh: true, overwrite: false, triggeredBy: 'admin@test.com');
    $job->handle($importer);
});

it('updates cache status during import', function () {
    $result = new ImportResult(newCount: 2, updatedCount: 1);

    $importer = Mockery::mock(TranslationImporter::class);
    $importer->shouldReceive('import')->andReturn($result);

    $job = new ImportTranslationsJob;
    $job->handle($importer);

    $status = Cache::get('translations.import.status');
    expect($status['running'])->toBeFalse()
        ->and($status['progress'])->toBe(100)
        ->and($status['message'])->toContain('2 new keys')
        ->and($status['message'])->toContain('1 updated');
});

it('defaults to non-fresh overwrite import', function () {
    $result = new ImportResult;

    $importer = Mockery::mock(TranslationImporter::class);
    $importer->shouldReceive('import')
        ->once()
        ->with(Mockery::on(fn ($options) => $options['fresh'] === false && $options['overwrite'] === true))
        ->andReturn($result);

    $job = new ImportTranslationsJob;
    $job->handle($importer);
});
