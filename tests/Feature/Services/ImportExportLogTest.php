<?php

use Outhebox\Translations\Models\ExportLog;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\ImportLog;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\Exporter\TranslationExporter;
use Outhebox\Translations\Services\Importer\TranslationImporter;

beforeEach(function () {
    useContributorAuth();
    $this->source = Language::factory()->source()->create();
});

it('creates import log with accurate counts', function () {
    $langPath = lang_path();
    if (! is_dir($langPath.'/en')) {
        mkdir($langPath.'/en', 0755, true);
    }
    file_put_contents($langPath.'/en/messages.php', "<?php\nreturn ['hello' => 'Hello', 'goodbye' => 'Goodbye'];");

    $importer = app(TranslationImporter::class);
    $result = $importer->import([
        'triggered_by' => 1,
        'source' => 'test',
        'fresh' => false,
    ]);

    $log = ImportLog::query()->latest()->first();

    expect($log)->not->toBeNull();
    expect($log->new_count)->toBe($result->newCount);
    expect($log->updated_count)->toBe($result->updatedCount);
    expect($log->key_count)->toBe($result->keyCount);
    expect($log->locale_count)->toBe($result->localeCount);
    expect($log->duration_ms)->toBeGreaterThanOrEqual(0);
    expect($log->triggered_by)->toBe('1');
    expect($log->source)->toBe('test');
    expect($log->fresh)->toBeFalse();

    @unlink($langPath.'/en/messages.php');
    @rmdir($langPath.'/en');
});

it('creates import log with fresh flag', function () {
    $langPath = lang_path();
    if (! is_dir($langPath.'/en')) {
        mkdir($langPath.'/en', 0755, true);
    }
    file_put_contents($langPath.'/en/test.php', "<?php\nreturn ['key' => 'value'];");

    $importer = app(TranslationImporter::class);
    $importer->import(['fresh' => true, 'source' => 'cli']);

    $log = ImportLog::query()->latest()->first();
    expect($log->fresh)->toBeTrue();
    expect($log->source)->toBe('cli');

    @unlink($langPath.'/en/test.php');
    @rmdir($langPath.'/en');
});

it('creates export log with accurate counts', function () {
    $group = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);
    $key = TranslationKey::factory()->for($group)->create(['key' => 'hello']);
    Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->source->id,
        'value' => 'Hello',
        'status' => 'translated',
    ]);

    $exporter = app(TranslationExporter::class);
    $result = $exporter->export([
        'triggered_by' => 2,
        'source' => 'test',
    ]);

    $log = ExportLog::query()->latest()->first();

    expect($log)->not->toBeNull();
    expect($log->file_count)->toBe($result->fileCount);
    expect($log->key_count)->toBe($result->keyCount);
    expect($log->locale_count)->toBe($result->localeCount);
    expect($log->duration_ms)->toBeGreaterThanOrEqual(0);
    expect($log->triggered_by)->toBe('2');
    expect($log->source)->toBe('test');
});
