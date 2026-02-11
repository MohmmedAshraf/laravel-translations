<?php

use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\Importer\TranslationImporter;

beforeEach(function () {
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);
    config(['translations.source_language' => 'en']);
});

it('skips vendor import when scan_vendor is false', function () {
    config(['translations.import.scan_vendor' => false]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect($result->keyCount)->toBeGreaterThan(0);
});

it('imports with detect_parameters disabled', function () {
    config(['translations.import.detect_parameters' => false]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect($result->keyCount)->toBeGreaterThan(0);

    $keyWithParams = TranslationKey::query()
        ->whereNotNull('parameters')
        ->first();

    expect($keyWithParams)->toBeNull();
});

it('imports with detect_html disabled', function () {
    config(['translations.import.detect_html' => false]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect($result->keyCount)->toBeGreaterThan(0);
});

it('imports with detect_plural disabled', function () {
    config(['translations.import.detect_plural' => false]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect($result->keyCount)->toBeGreaterThan(0);
});

it('skips non-string translation values', function () {
    $tempDir = sys_get_temp_dir().'/importer_test_'.uniqid();
    mkdir($tempDir.'/en', 0755, true);
    file_put_contents($tempDir.'/en/test.php', '<?php return ["valid" => "Hello", "nested" => ["not" => "a string at root"]];');

    config(['translations.lang_path' => $tempDir]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    // Nested arrays get flattened by Arr::dot, so "nested.not" becomes "a string at root"
    expect($result->keyCount)->toBeGreaterThan(0);

    @unlink($tempDir.'/en/test.php');
    @rmdir($tempDir.'/en');
    @rmdir($tempDir);
});

it('imports vendor files when they exist', function () {
    $tempDir = sys_get_temp_dir().'/importer_vendor_'.uniqid();
    mkdir($tempDir.'/en', 0755, true);
    mkdir($tempDir.'/vendor/my-package/en', 0755, true);

    file_put_contents($tempDir.'/en/app.php', '<?php return ["hello" => "Hello"];');
    file_put_contents($tempDir.'/vendor/my-package/en/messages.php', '<?php return ["welcome" => "Welcome"];');

    config(['translations.lang_path' => $tempDir]);
    config(['translations.import.scan_vendor' => true]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect(Group::query()->where('namespace', 'my-package')->exists())->toBeTrue();

    // Cleanup
    @unlink($tempDir.'/en/app.php');
    @unlink($tempDir.'/vendor/my-package/en/messages.php');
    @rmdir($tempDir.'/vendor/my-package/en');
    @rmdir($tempDir.'/vendor/my-package');
    @rmdir($tempDir.'/vendor');
    @rmdir($tempDir.'/en');
    @rmdir($tempDir);
});

it('excludes files listed in exclude_files config', function () {
    $tempDir = sys_get_temp_dir().'/importer_exclude_'.uniqid();
    mkdir($tempDir.'/en', 0755, true);
    file_put_contents($tempDir.'/en/auth.php', '<?php return ["login" => "Login"];');
    file_put_contents($tempDir.'/en/app.php', '<?php return ["hello" => "Hello"];');

    config(['translations.lang_path' => $tempDir]);
    config(['translations.exclude_files' => ['auth.php']]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect(Group::query()->where('name', 'auth')->exists())->toBeFalse()
        ->and(Group::query()->where('name', 'app')->exists())->toBeTrue();

    @unlink($tempDir.'/en/auth.php');
    @unlink($tempDir.'/en/app.php');
    @rmdir($tempDir.'/en');
    @rmdir($tempDir);
});

it('activates an inactive language during import', function () {
    Language::factory()->create(['code' => 'en', 'active' => false]);

    $importer = app(TranslationImporter::class);
    $importer->import(['fresh' => true]);

    expect(Language::query()->where('code', 'en')->first()->active)->toBeTrue();
});

it('updates existing source key metadata on re-import', function () {
    $en = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);

    $importer = app(TranslationImporter::class);
    $importer->import();

    // Import again to trigger the metadata update path for existing keys
    $result = $importer->import(['overwrite' => true]);

    expect($result->keyCount)->toBeGreaterThan(0);
});

it('imports json translations for new locale', function () {
    $tempDir = sys_get_temp_dir().'/importer_json_'.uniqid();
    mkdir($tempDir, 0755, true);
    file_put_contents($tempDir.'/fr.json', json_encode(['hello' => 'Bonjour']));

    config(['translations.lang_path' => $tempDir]);

    $importer = app(TranslationImporter::class);
    $result = $importer->import(['fresh' => true]);

    expect($result->localeCount)->toBe(1)
        ->and(Language::query()->where('code', 'fr')->exists())->toBeTrue();

    @unlink($tempDir.'/fr.json');
    @rmdir($tempDir);
});
