<?php

use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Rules\TranslationPluralRule;

it('passes for non-plural key', function () {
    $key = TranslationKey::factory()->create(['is_plural' => false]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'Regular text'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when no source translation exists', function () {
    Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'One segment only'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes with correct 2-part variant count', function () {
    $sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $sourceLanguage->id,
        'value' => 'one item|many items',
    ]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'un élément|plusieurs éléments'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes with correct 3-part variant count', function () {
    $sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $sourceLanguage->id,
        'value' => '{0} none|{1} one|[2,*] many',
    ]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => '{0} aucun|{1} un|[2,*] plusieurs'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('fails when too few variants', function () {
    $sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $sourceLanguage->id,
        'value' => '{0} none|{1} one|[2,*] many',
    ]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'un|plusieurs'],
        ['value' => [$rule]],
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('value'))->toContain('3 variants')
        ->and($validator->errors()->first('value'))->toContain('got 2');
});

it('fails when too many variants', function () {
    $sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $sourceLanguage->id,
        'value' => 'one|many',
    ]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'zero|un|plusieurs'],
        ['value' => [$rule]],
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('value'))->toContain('2 variants')
        ->and($validator->errors()->first('value'))->toContain('got 3');
});

it('passes when value is empty', function () {
    $sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $sourceLanguage->id,
        'value' => 'one|many',
    ]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => ''],
        ['value' => ['nullable', $rule]],
    );

    expect($validator->passes())->toBeTrue();
});

it('passes when source has single variant with no pipes', function () {
    $sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $key = TranslationKey::factory()->create(['is_plural' => true]);
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $sourceLanguage->id,
        'value' => 'just one form',
    ]);

    $rule = new TranslationPluralRule($key);

    $validator = $this->app['validator']->make(
        ['value' => 'une seule forme'],
        ['value' => [$rule]],
    );

    expect($validator->passes())->toBeTrue();
});
