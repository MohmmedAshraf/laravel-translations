<?php

use Outhebox\Translations\Models\ExportLog;

it('creates an export log', function () {
    $log = ExportLog::factory()->create([
        'locale_count' => 3,
        'file_count' => 10,
        'key_count' => 50,
        'duration_ms' => 1500,
        'triggered_by' => 'admin@example.com',
        'source' => 'cli',
    ]);

    expect($log->locale_count)->toBe(3)
        ->and($log->file_count)->toBe(10)
        ->and($log->key_count)->toBe(50)
        ->and($log->duration_ms)->toBe(1500)
        ->and($log->triggered_by)->toBe('admin@example.com')
        ->and($log->source)->toBe('cli');
});

it('uses the correct table', function () {
    $log = new ExportLog;

    expect($log->getTable())->toBe('ltu_export_logs');
});
