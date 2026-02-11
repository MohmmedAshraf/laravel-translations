<?php

use Illuminate\Support\Facades\Event;
use Outhebox\Translations\Events\ImportCompleted;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\ImportLog;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\Importer\TranslationImporter;

beforeEach(function () {
    $this->importer = app(TranslationImporter::class);
    $this->langPath = sys_get_temp_dir().'/ltu-import-test-'.uniqid();
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

it('imports php translation files', function () {
    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");

    $result = $this->importer->import();

    expect($result->localeCount)->toBe(1)
        ->and($result->keyCount)->toBe(1)
        ->and($result->newCount)->toBe(1)
        ->and(TranslationKey::query()->where('key', 'welcome')->exists())->toBeTrue();
});

it('imports json translation files', function () {
    file_put_contents($this->langPath.'/fr.json', json_encode(['Hello' => 'Bonjour']));

    $result = $this->importer->import();

    expect($result->keyCount)->toBe(1)
        ->and(TranslationKey::query()->where('key', 'Hello')->exists())->toBeTrue();
});

it('creates languages from lang directory', function () {
    mkdir($this->langPath.'/fr', 0755, true);
    file_put_contents($this->langPath.'/fr/messages.php', "<?php\nreturn ['hello' => 'Bonjour'];");

    $this->importer->import();

    $language = Language::query()->where('code', 'fr')->first();
    expect($language)->not->toBeNull()
        ->and($language->name)->toBe('French')
        ->and($language->active)->toBeTrue();
});

it('clears all translations when fresh option is used', function () {
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->create(['group_id' => $group->id]);
    Translation::factory()->create(['translation_key_id' => $key->id]);

    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['new_key' => 'New'];");

    $result = $this->importer->import(['fresh' => true]);

    expect(TranslationKey::query()->where('key', $key->key)->exists())->toBeFalse()
        ->and(TranslationKey::query()->where('key', 'new_key')->exists())->toBeTrue();
});

it('overwrites existing translations when overwrite is true', function () {
    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");
    $this->importer->import();

    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome Updated'];");
    $result = $this->importer->import(['overwrite' => true]);

    $translation = Translation::query()->whereHas('translationKey', fn ($q) => $q->where('key', 'welcome'))->first();

    expect($translation->value)->toBe('Welcome Updated')
        ->and($result->updatedCount)->toBeGreaterThanOrEqual(1);
});

it('does not overwrite when overwrite is false', function () {
    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");
    $this->importer->import();

    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome Updated'];");
    $result = $this->importer->import(['overwrite' => false]);

    $translation = Translation::query()->whereHas('translationKey', fn ($q) => $q->where('key', 'welcome'))->first();

    expect($translation->value)->toBe('Welcome')
        ->and($result->updatedCount)->toBe(0);
});

it('creates import log', function () {
    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");

    $this->importer->import(['triggered_by' => 'test@test.com', 'source' => 'ui']);

    $log = ImportLog::query()->latest()->first();

    expect($log)->not->toBeNull()
        ->and($log->triggered_by)->toBe('test@test.com')
        ->and($log->source)->toBe('ui');
});

it('dispatches ImportCompleted event', function () {
    Event::fake([ImportCompleted::class]);

    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");

    $this->importer->import();

    Event::assertDispatched(ImportCompleted::class);
});

it('creates groups for translation files', function () {
    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/auth.php', "<?php\nreturn ['failed' => 'Invalid credentials'];");

    $this->importer->import();

    expect(Group::query()->where('name', 'auth')->exists())->toBeTrue();
});

it('imports multiple locales', function () {
    mkdir($this->langPath.'/en', 0755, true);
    mkdir($this->langPath.'/fr', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");
    file_put_contents($this->langPath.'/fr/messages.php', "<?php\nreturn ['welcome' => 'Bienvenue'];");

    $result = $this->importer->import();

    expect($result->localeCount)->toBe(2)
        ->and(Language::query()->where('code', 'en')->exists())->toBeTrue()
        ->and(Language::query()->where('code', 'fr')->exists())->toBeTrue();
});

it('excludes configured files', function () {
    config(['translations.exclude_files' => ['secret.php']]);

    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");
    file_put_contents($this->langPath.'/en/secret.php', "<?php\nreturn ['key' => 'Secret'];");

    $this->importer->import();

    expect(Group::query()->where('name', 'messages')->exists())->toBeTrue()
        ->and(Group::query()->where('name', 'secret')->exists())->toBeFalse();
});

it('reactivates an inactive language on import', function () {
    Language::factory()->create(['code' => 'en', 'active' => false]);

    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");

    $this->importer->import();

    expect(Language::query()->where('code', 'en')->first()->active)->toBeTrue();
});

it('measures duration', function () {
    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['welcome' => 'Welcome'];");

    $result = $this->importer->import();

    expect($result->durationMs)->toBeGreaterThanOrEqual(0);
});

it('detects parameters in translation values', function () {
    config(['translations.import.detect_parameters' => true]);

    mkdir($this->langPath.'/en', 0755, true);
    file_put_contents($this->langPath.'/en/messages.php', "<?php\nreturn ['greeting' => 'Hello :name, you have :count messages'];");

    $this->importer->import();

    $key = TranslationKey::query()->where('key', 'greeting')->first();

    expect($key->parameters)->toContain(':name')
        ->and($key->parameters)->toContain(':count');
});
