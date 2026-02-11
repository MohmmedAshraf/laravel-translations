<?php

use Illuminate\Support\Facades\Event;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\ExportCompleted;
use Outhebox\Translations\Models\ExportLog;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\Exporter\TranslationExporter;

beforeEach(function () {
    $this->exporter = app(TranslationExporter::class);
    $this->langPath = sys_get_temp_dir().'/ltu-export-test-'.uniqid();
    mkdir($this->langPath, 0755, true);
    config(['translations.lang_path' => $this->langPath]);
});

afterEach(function () {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($this->langPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $file) {
        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
    }
    rmdir($this->langPath);
});

it('exports translations to php files', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'welcome']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Bienvenue',
        'status' => TranslationStatus::Translated,
    ]);

    $result = $this->exporter->export();

    expect($result->fileCount)->toBe(1)
        ->and($result->keyCount)->toBe(1)
        ->and($result->localeCount)->toBe(1)
        ->and(file_exists($this->langPath.'/fr/messages.php'))->toBeTrue();
});

it('exports translations to json files', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->json()->create();
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'Hello']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Bonjour',
        'status' => TranslationStatus::Translated,
    ]);

    $result = $this->exporter->export();

    expect($result->fileCount)->toBe(1)
        ->and(file_exists($this->langPath.'/fr.json'))->toBeTrue();
});

it('skips untranslated entries', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'welcome']);
    Translation::factory()->untranslated()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
    ]);

    $result = $this->exporter->export();

    expect($result->fileCount)->toBe(0)
        ->and($result->keyCount)->toBe(0);
});

it('filters by locale', function () {
    $fr = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $de = Language::factory()->create(['code' => 'de', 'active' => true]);
    $group = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'welcome']);

    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $fr->id, 'value' => 'Bienvenue', 'status' => TranslationStatus::Translated]);
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $de->id, 'value' => 'Willkommen', 'status' => TranslationStatus::Translated]);

    $result = $this->exporter->export(['locale' => 'fr']);

    expect($result->localeCount)->toBe(1)
        ->and(file_exists($this->langPath.'/fr/messages.php'))->toBeTrue()
        ->and(file_exists($this->langPath.'/de/messages.php'))->toBeFalse();
});

it('filters by group', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $messages = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);
    $auth = Group::factory()->create(['name' => 'auth', 'file_format' => 'php']);

    $key1 = TranslationKey::factory()->create(['group_id' => $messages->id, 'key' => 'welcome']);
    $key2 = TranslationKey::factory()->create(['group_id' => $auth->id, 'key' => 'login']);

    Translation::factory()->create(['translation_key_id' => $key1->id, 'language_id' => $language->id, 'value' => 'Bienvenue', 'status' => TranslationStatus::Translated]);
    Translation::factory()->create(['translation_key_id' => $key2->id, 'language_id' => $language->id, 'value' => 'Connexion', 'status' => TranslationStatus::Translated]);

    $result = $this->exporter->export(['group' => 'messages']);

    expect($result->fileCount)->toBe(1)
        ->and(file_exists($this->langPath.'/fr/messages.php'))->toBeTrue()
        ->and(file_exists($this->langPath.'/fr/auth.php'))->toBeFalse();
});

it('skips inactive languages', function () {
    Language::factory()->create(['code' => 'fr', 'active' => false]);

    $result = $this->exporter->export();

    expect($result->localeCount)->toBe(0);
});

it('creates an export log', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'welcome']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Bienvenue',
        'status' => TranslationStatus::Translated,
    ]);

    $this->exporter->export(['triggered_by' => 'test@test.com', 'source' => 'ui']);

    $log = ExportLog::query()->latest()->first();

    expect($log)->not->toBeNull()
        ->and($log->triggered_by)->toBe('test@test.com')
        ->and($log->source)->toBe('ui');
});

it('dispatches ExportCompleted event', function () {
    Event::fake([ExportCompleted::class]);

    Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->exporter->export();

    Event::assertDispatched(ExportCompleted::class);
});

it('exports vendor namespaced groups', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->vendor('my-package')->create(['name' => 'messages', 'file_format' => 'php']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'welcome']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Bienvenue',
        'status' => TranslationStatus::Translated,
    ]);

    $result = $this->exporter->export();

    expect($result->fileCount)->toBe(1)
        ->and(file_exists($this->langPath.'/vendor/my-package/fr/messages.php'))->toBeTrue();
});

it('only exports approved translations when require_approval is enabled', function () {
    config(['translations.export.require_approval' => true]);

    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->create(['name' => 'messages', 'file_format' => 'php']);

    $key1 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'approved_key']);
    $key2 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'translated_key']);

    Translation::factory()->approved()->create(['translation_key_id' => $key1->id, 'language_id' => $language->id, 'value' => 'Approuvé']);
    Translation::factory()->create(['translation_key_id' => $key2->id, 'language_id' => $language->id, 'value' => 'Traduit', 'status' => TranslationStatus::Translated]);

    $result = $this->exporter->export();

    expect($result->keyCount)->toBe(1);
});

it('measures duration', function () {
    Language::factory()->create(['code' => 'fr', 'active' => true]);

    $result = $this->exporter->export();

    expect($result->durationMs)->toBeGreaterThanOrEqual(0);
});
