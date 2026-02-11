<?php

use Outhebox\Translations\Services\Exporter\ExportResult;

it('initializes with default zero values', function () {
    $result = new ExportResult;

    expect($result->localeCount)->toBe(0)
        ->and($result->fileCount)->toBe(0)
        ->and($result->keyCount)->toBe(0)
        ->and($result->durationMs)->toBe(0);
});

it('accepts constructor values', function () {
    $result = new ExportResult(
        localeCount: 3,
        fileCount: 10,
        keyCount: 500,
        durationMs: 1500,
    );

    expect($result->localeCount)->toBe(3)
        ->and($result->fileCount)->toBe(10)
        ->and($result->keyCount)->toBe(500)
        ->and($result->durationMs)->toBe(1500);
});

it('allows incrementing values', function () {
    $result = new ExportResult;
    $result->localeCount++;
    $result->fileCount += 5;
    $result->keyCount += 100;

    expect($result->localeCount)->toBe(1)
        ->and($result->fileCount)->toBe(5)
        ->and($result->keyCount)->toBe(100);
});
