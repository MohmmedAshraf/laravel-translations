<?php

use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\ExportLog;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    $this->tempDir = sys_get_temp_dir().'/translations_export_test_'.uniqid();
    mkdir($this->tempDir, 0755, true);
    config(['translations.lang_path' => $this->tempDir]);
});

afterEach(function () {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($this->tempDir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iterator as $file) {
        $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    }
    rmdir($this->tempDir);
});

it('exports PHP translation files', function () {
    $en = Language::factory()->create(['code' => 'en']);
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'failed']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
        'value' => 'Auth failed',
        'status' => TranslationStatus::Translated,
    ]);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(file_exists($this->tempDir.'/en/auth.php'))->toBeTrue();

    $content = require $this->tempDir.'/en/auth.php';
    expect($content['failed'])->toBe('Auth failed');
});

it('exports JSON translation files', function () {
    $fr = Language::factory()->create(['code' => 'fr']);
    $group = Group::factory()->json()->create();
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'Hello']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $fr->id,
        'value' => 'Bonjour',
        'status' => TranslationStatus::Translated,
    ]);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(file_exists($this->tempDir.'/fr.json'))->toBeTrue();

    $content = json_decode(file_get_contents($this->tempDir.'/fr.json'), true);
    expect($content['Hello'])->toBe('Bonjour');
});

it('filters export by locale', function () {
    $en = Language::factory()->create(['code' => 'en']);
    $fr = Language::factory()->create(['code' => 'fr']);
    $group = Group::factory()->create(['name' => 'test']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'hello']);

    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $en->id, 'value' => 'Hello']);
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $fr->id, 'value' => 'Bonjour']);

    $this->artisan('translations:export', ['--locale' => 'en', '--no-interaction' => true])
        ->assertSuccessful();

    expect(file_exists($this->tempDir.'/en/test.php'))->toBeTrue();
    expect(file_exists($this->tempDir.'/fr/test.php'))->toBeFalse();
});

it('filters export by group', function () {
    $en = Language::factory()->create(['code' => 'en']);
    $group1 = Group::factory()->create(['name' => 'auth']);
    $group2 = Group::factory()->create(['name' => 'messages']);

    $key1 = TranslationKey::factory()->create(['group_id' => $group1->id, 'key' => 'failed']);
    $key2 = TranslationKey::factory()->create(['group_id' => $group2->id, 'key' => 'welcome']);

    Translation::factory()->create(['translation_key_id' => $key1->id, 'language_id' => $en->id, 'value' => 'Failed']);
    Translation::factory()->create(['translation_key_id' => $key2->id, 'language_id' => $en->id, 'value' => 'Welcome']);

    $this->artisan('translations:export', ['--group' => 'auth', '--no-interaction' => true])
        ->assertSuccessful();

    expect(file_exists($this->tempDir.'/en/auth.php'))->toBeTrue();
    expect(file_exists($this->tempDir.'/en/messages.php'))->toBeFalse();
});

it('creates an export log entry', function () {
    $en = Language::factory()->create(['code' => 'en']);
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'test']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
        'value' => 'Test',
    ]);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(ExportLog::query()->count())->toBe(1);

    $log = ExportLog::query()->first();
    expect($log->file_count)->toBeGreaterThan(0);
});

it('skips untranslated entries on export', function () {
    $en = Language::factory()->create(['code' => 'en']);
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'test']);
    Translation::factory()->untranslated()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
    ]);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(file_exists($this->tempDir.'/en/auth.php'))->toBeFalse();
});

it('handles export with no translations', function () {
    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(ExportLog::query()->count())->toBe(1);
});

it('skips inactive languages on export', function () {
    $en = Language::factory()->create(['code' => 'en', 'active' => true]);
    $de = Language::factory()->create(['code' => 'de', 'active' => false]);
    $group = Group::factory()->create(['name' => 'auth']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'test']);

    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $en->id, 'value' => 'Test']);
    Translation::factory()->create(['translation_key_id' => $key->id, 'language_id' => $de->id, 'value' => 'Test DE']);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    expect(file_exists($this->tempDir.'/en/auth.php'))->toBeTrue()
        ->and(file_exists($this->tempDir.'/de/auth.php'))->toBeFalse();
});

it('unflattens dot notation on export', function () {
    $en = Language::factory()->create(['code' => 'en']);
    $group = Group::factory()->create(['name' => 'validation']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'custom.email.required']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
        'value' => 'Email is required',
    ]);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->assertSuccessful();

    $content = require $this->tempDir.'/en/validation.php';
    expect($content['custom']['email']['required'])->toBe('Email is required');
});

it('displays export results in table format', function () {
    Language::factory()->create(['code' => 'en']);

    $this->artisan('translations:export', ['--no-interaction' => true])
        ->expectsOutputToContain('Export completed')
        ->assertSuccessful();
});
