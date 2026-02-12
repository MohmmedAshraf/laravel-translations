<?php

use Outhebox\Translations\Models\ImportLog;

it('creates an import log', function () {
    $log = ImportLog::factory()->create([
        'locale_count' => 2,
        'key_count' => 100,
        'new_count' => 50,
        'updated_count' => 30,
        'duration_ms' => 2500,
        'triggered_by' => 'admin@example.com',
        'source' => 'ui',
        'fresh' => true,
    ]);

    expect($log->locale_count)->toBe(2)
        ->and($log->key_count)->toBe(100)
        ->and($log->new_count)->toBe(50)
        ->and($log->updated_count)->toBe(30)
        ->and($log->duration_ms)->toBe(2500)
        ->and($log->triggered_by)->toBe('admin@example.com')
        ->and($log->source)->toBe('ui')
        ->and($log->fresh)->toBeTrue();
});

it('uses the correct table', function () {
    $log = new ImportLog;

    expect($log->getTable())->toBe('ltu_import_logs');
});

it('casts fresh to boolean', function () {
    $log = ImportLog::factory()->create(['fresh' => false]);

    expect($log->fresh)->toBeFalse();
});
