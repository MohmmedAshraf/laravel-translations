<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\ImportLog;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    config(['translations.lang_path' => __DIR__.'/../../Fixtures/lang']);
    $this->contributor = Contributor::factory()->owner()->create();
    $this->tempDirs = [];
});

afterEach(function () {
    foreach ($this->tempDirs as $dir) {
        if (! is_dir($dir)) {
            continue;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $file) {
            $file->isDir() ? @rmdir($file->getPathname()) : @unlink($file->getPathname());
        }
        @rmdir($dir);
    }
});

it('imports translations via HTTP', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(TranslationKey::query()->count())->toBeGreaterThan(0);
    expect(ImportLog::query()->count())->toBe(1);
});

it('activates pre-existing inactive languages on import via HTTP', function () {
    Language::factory()->create(['code' => 'en', 'active' => false, 'is_source' => true]);
    Language::factory()->create(['code' => 'fr', 'active' => false]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(Language::query()->where('code', 'en')->first()->active)->toBeTrue();
    expect(Language::query()->where('code', 'fr')->first()->active)->toBeTrue();
});

it('imports with fresh option', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'));

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'), ['fresh' => true])
        ->assertRedirect();

    expect(ImportLog::query()->count())->toBe(2);
});

it('exports translations via HTTP', function () {
    $tempDir = sys_get_temp_dir().'/translations_export_http_'.uniqid();
    mkdir($tempDir, 0755, true);
    $this->tempDirs[] = $tempDir;
    config(['translations.lang_path' => $tempDir]);

    $en = Language::factory()->create(['code' => 'en']);
    $group = Group::factory()->create(['name' => 'test']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'hello']);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $en->id,
        'value' => 'Hello',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.export'))
        ->assertRedirect()
        ->assertSessionHas('success');
});

it('restricts import to admin role', function () {
    $reviewer = Contributor::factory()->reviewer()->create();

    $this->actingAs($reviewer, 'translations')
        ->post(route('ltu.import'))
        ->assertForbidden();
});

it('restricts export to admin role', function () {
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($translator, 'translations')
        ->post(route('ltu.export'))
        ->assertForbidden();
});

it('returns import status as JSON', function () {
    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.import.status'))
        ->assertOk()
        ->assertJsonStructure(['running', 'progress', 'message']);
});

it('returns export status as JSON', function () {
    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.export.status'))
        ->assertOk()
        ->assertJsonStructure(['running', 'progress', 'message']);
});
