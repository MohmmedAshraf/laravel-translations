<?php

use Outhebox\Translations\Services\Importer\ImportResult;

it('initializes with default zero values', function () {
    $result = new ImportResult;

    expect($result->localeCount)->toBe(0)
        ->and($result->keyCount)->toBe(0)
        ->and($result->newCount)->toBe(0)
        ->and($result->updatedCount)->toBe(0)
        ->and($result->durationMs)->toBe(0);
});

it('accepts constructor values', function () {
    $result = new ImportResult(
        localeCount: 3,
        keyCount: 500,
        newCount: 100,
        updatedCount: 50,
        durationMs: 2500,
    );

    expect($result->localeCount)->toBe(3)
        ->and($result->keyCount)->toBe(500)
        ->and($result->newCount)->toBe(100)
        ->and($result->updatedCount)->toBe(50)
        ->and($result->durationMs)->toBe(2500);
});

it('merges another result', function () {
    $result1 = new ImportResult(localeCount: 2, keyCount: 100, newCount: 10, updatedCount: 5);
    $result2 = new ImportResult(localeCount: 3, keyCount: 200, newCount: 20, updatedCount: 15);

    $result1->merge($result2);

    expect($result1->localeCount)->toBe(5)
        ->and($result1->keyCount)->toBe(300)
        ->and($result1->newCount)->toBe(30)
        ->and($result1->updatedCount)->toBe(20);
});

it('merge does not affect duration', function () {
    $result1 = new ImportResult(durationMs: 100);
    $result2 = new ImportResult(durationMs: 200);

    $result1->merge($result2);

    expect($result1->durationMs)->toBe(100);
});
