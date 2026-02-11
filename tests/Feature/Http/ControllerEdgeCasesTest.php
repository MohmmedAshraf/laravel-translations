<?php

use Illuminate\Support\Facades\Event;
use Outhebox\Translations\Events\LanguageAdded;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\Importer\TranslationImporter;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
    $this->sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
});

// ── PhraseController edge cases ──

it('handles null source language in phrase index', function () {
    $this->sourceLanguage->update(['is_source' => false]);
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.index', $language))
        ->assertOk();
});

it('handles phrase edit with no source language', function () {
    $this->sourceLanguage->update(['is_source' => false]);
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', ['language' => $language, 'translationKey' => $key]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('sourceLanguage', null)
            ->where('sourceTranslation', null)
        );
});

it('returns empty similar keys when prefix is too short', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->create(['name' => 'test']);
    $key = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'a']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', ['language' => $language, 'translationKey' => $key]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('similarKeys', [])
        );
});

it('finds similar keys for phrases with common prefix', function () {
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);
    $group = Group::factory()->create(['name' => 'auth']);
    $key1 = TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'login_title']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'login_button']);
    TranslationKey::factory()->create(['group_id' => $group->id, 'key' => 'login_error']);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', ['language' => $language, 'translationKey' => $key1]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('similarKeys', 2)
        );
});

// ── LanguageController edge cases ──

it('filters languages by completed status', function () {
    $keys = TranslationKey::factory()->count(2)->create();
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    foreach ($keys as $key) {
        Translation::factory()->create([
            'translation_key_id' => $key->id,
            'language_id' => $language->id,
            'status' => 'translated',
        ]);
    }

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'completed']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
            ->where('data.data.0.code', 'fr')
        );
});

it('filters languages by needs_review status', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'status' => 'needs_review',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'needs_review']]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('data.total', 1)
        );
});

it('handles default filter value in language status', function () {
    Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index', ['filter' => ['status' => 'unknown_status']]))
        ->assertOk();
});

it('resolves completed language status', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(function ($page) {
            $data = $page->toArray()['props']['data']['data'];
            $fr = collect($data)->firstWhere('code', 'fr');
            expect($fr['status'])->toBe('completed');
        });
});

it('resolves needs_review language status', function () {
    $key = TranslationKey::factory()->create();
    $language = Language::factory()->create(['code' => 'fr', 'active' => true]);

    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'status' => 'needs_review',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.languages.index'))
        ->assertOk()
        ->assertInertia(function ($page) {
            $data = $page->toArray()['props']['data']['data'];
            $fr = collect($data)->firstWhere('code', 'fr');
            expect($fr['status'])->toBe('needs_review');
        });
});

it('stores custom language', function () {
    Event::fake([LanguageAdded::class]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'tlh',
            'name' => 'Klingon',
            'native_name' => 'tlhIngan Hol',
            'rtl' => false,
        ])
        ->assertRedirect();

    expect(Language::query()->where('code', 'tlh')->exists())->toBeTrue();
    Event::assertDispatched(LanguageAdded::class);
});

it('skips already active languages when enabling', function () {
    $active = Language::factory()->create(['code' => 'fr', 'active' => true]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$active->id],
        ])
        ->assertRedirect()
        ->assertSessionHas('info');
});

it('prevents deleting source language', function () {
    $this->actingAs($this->contributor, 'translations')
        ->delete(route('ltu.languages.destroy', $this->sourceLanguage))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot remove the source language.');
});

it('skips source language in bulk delete', function () {
    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.languages.bulk-action', 'delete'), [
            'ids' => [$this->sourceLanguage->id],
        ])
        ->assertRedirect()
        ->assertSessionHas('warning');
});

// ── ImportExportController edge cases ──

it('handles import error with catch block', function () {
    config(['translations.queue.connection' => null]);

    $mock = Mockery::mock(TranslationImporter::class);
    $mock->shouldReceive('import')->andThrow(new RuntimeException('Import failed'));
    app()->instance(TranslationImporter::class, $mock);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.import'))
        ->assertRedirect()
        ->assertSessionHas('error');
});

it('handles export error with catch block', function () {
    config(['translations.lang_path' => '/nonexistent/path']);
    config(['translations.queue.connection' => null]);

    $lang = Language::factory()->create(['code' => 'fr']);
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->create(['group_id' => $group->id]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $lang->id,
        'value' => 'Test',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->post(route('ltu.export'))
        ->assertRedirect();
});

it('returns import status json', function () {
    $this->actingAs($this->contributor, 'translations')
        ->getJson(route('ltu.import.status'))
        ->assertOk()
        ->assertJsonStructure(['running', 'progress', 'message']);
});

it('returns export status json', function () {
    $this->actingAs($this->contributor, 'translations')
        ->getJson(route('ltu.export.status'))
        ->assertOk()
        ->assertJsonStructure(['running', 'progress', 'message']);
});

// ── ContributorController edge cases ──

it('prevents admin from assigning owner role in update', function () {
    $admin = Contributor::factory()->admin()->create();
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($admin, 'translations')
        ->put(route('ltu.contributors.update', $translator), [
            'role' => 'owner',
            'language_ids' => [],
        ])
        ->assertSessionHasErrors('role');
});

it('prevents self-demotion', function () {
    $owner = Contributor::factory()->owner()->create();

    $this->actingAs($owner, 'translations')
        ->put(route('ltu.contributors.update', $owner), [
            'role' => 'translator',
            'language_ids' => [],
        ])
        ->assertSessionHasErrors('role');
});

it('prevents deactivating last owner via toggle', function () {
    // $this->contributor is already an owner, so make a second admin to do the toggle
    $admin = Contributor::factory()->admin()->create();

    // Toggle $this->contributor (the only owner)
    $this->actingAs($admin, 'translations')
        ->post(route('ltu.contributors.toggle-active', $this->contributor))
        ->assertSessionHas('error', 'Cannot deactivate the last owner.');
});

it('shows contributors index page', function () {
    Contributor::factory()->admin()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.contributors.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('translations/contributors/index')
            ->has('data')
            ->has('tableConfig')
            ->has('languages')
            ->has('roles')
        );
});
