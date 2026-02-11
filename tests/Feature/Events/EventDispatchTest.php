<?php

use Illuminate\Support\Facades\Event;
use Outhebox\Translations\Events\ImportCompleted;
use Outhebox\Translations\Events\LanguageAdded;
use Outhebox\Translations\Events\TranslationKeyCreated;
use Outhebox\Translations\Events\TranslationSaved;
use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->owner = Contributor::factory()->owner()->create();
    $this->source = Language::factory()->source()->create();
});

it('dispatches TranslationSaved when a translation value changes', function () {
    Event::fake([TranslationSaved::class]);

    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();
    $language = Language::factory()->create();

    Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Hello',
        'status' => 'translated',
    ]);

    Event::assertDispatched(TranslationSaved::class, function ($event) {
        return $event->translation->value === 'Hello';
    });
});

it('does not dispatch TranslationSaved when value does not change', function () {
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();
    $language = Language::factory()->create();

    $translation = Translation::query()->create([
        'translation_key_id' => $key->id,
        'language_id' => $language->id,
        'value' => 'Hello',
        'status' => 'translated',
    ]);

    $translation = $translation->fresh();

    Event::fake([TranslationSaved::class]);

    $translation->update(['status' => 'approved']);

    Event::assertNotDispatched(TranslationSaved::class);
});

it('dispatches LanguageAdded when activating a language', function () {
    Event::fake([LanguageAdded::class]);

    $language = Language::factory()->create(['active' => false]);

    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.languages.store'), [
            'language_ids' => [$language->id],
        ]);

    Event::assertDispatched(LanguageAdded::class, function ($event) use ($language) {
        return $event->language->id === $language->id;
    });
});

it('dispatches LanguageAdded when creating a custom language', function () {
    Event::fake([LanguageAdded::class]);

    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.languages.store-custom'), [
            'code' => 'xx',
            'name' => 'Custom Language',
            'native_name' => 'Custom',
            'rtl' => false,
        ]);

    Event::assertDispatched(LanguageAdded::class, function ($event) {
        return $event->language->code === 'xx';
    });
});

it('dispatches TranslationKeyCreated when creating a source key', function () {
    Event::fake([TranslationKeyCreated::class]);

    $group = Group::factory()->create();

    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.source.store'), [
            'group_id' => $group->id,
            'key' => 'test.new_key',
            'value' => 'Test value',
        ]);

    Event::assertDispatched(TranslationKeyCreated::class, function ($event) {
        return $event->translationKey->key === 'test.new_key';
    });
});

it('dispatches ImportCompleted after import', function () {
    Event::fake([ImportCompleted::class]);

    $langPath = lang_path();
    if (! is_dir($langPath.'/en')) {
        mkdir($langPath.'/en', 0755, true);
    }
    file_put_contents($langPath.'/en/test.php', "<?php\nreturn ['key' => 'value'];");

    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.import'));

    Event::assertDispatched(ImportCompleted::class);

    @unlink($langPath.'/en/test.php');
    @rmdir($langPath.'/en');
});
