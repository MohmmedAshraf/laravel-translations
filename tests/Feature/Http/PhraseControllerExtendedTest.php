<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->contributor = Contributor::factory()->owner()->create();
    $this->sourceLanguage = Language::factory()->create(['code' => 'en', 'is_source' => true, 'active' => true]);
    $this->language = Language::factory()->create(['code' => 'fr', 'is_source' => false, 'active' => true]);
});

it('resolves status as translated when approval workflow is disabled', function () {
    config(['translations.approval_workflow' => false]);

    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Test translation',
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->language->id)
        ->first();

    expect($translation->status->value)->toBe('translated');
});

it('resolves status as approved when user can approve translations', function () {
    config(['translations.approval_workflow' => true]);

    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Test translation',
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->language->id)
        ->first();

    expect($translation->status->value)->toBe('approved');
});

it('resolves status as needs_review for translator role', function () {
    config(['translations.approval_workflow' => true]);

    $translator = Contributor::factory()->translator()->create();
    $translator->languages()->attach($this->language);

    $key = TranslationKey::factory()->create();

    $this->actingAs($translator, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Test translation',
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->language->id)
        ->first();

    expect($translation->status->value)->toBe('needs_review');
});

it('allows explicit status to override auto-resolution', function () {
    config(['translations.approval_workflow' => true]);

    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Test translation',
            'status' => 'translated',
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->language->id)
        ->first();

    expect($translation->status->value)->toBe('translated');
});

it('forbids access when translator is not assigned to language', function () {
    $translator = Contributor::factory()->translator()->create();

    $this->actingAs($translator, 'translations')
        ->get(route('ltu.phrases.index', $this->language))
        ->assertForbidden();
});

it('includes workflow data in edit page', function () {
    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->get(route('ltu.phrases.edit', ['language' => $this->language, 'translationKey' => $key]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('workflow')
            ->where('workflow.canApprove', true)
            ->where('workflow.canEdit', true)
        );
});

it('sets translated_by when saving translation', function () {
    $key = TranslationKey::factory()->create();

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => 'Test',
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->language->id)
        ->first();

    expect($translation->translated_by)->toBe($this->contributor->id);
});

it('does not auto-set status when value is null', function () {
    $key = TranslationKey::factory()->create();
    Translation::factory()->create([
        'translation_key_id' => $key->id,
        'language_id' => $this->language->id,
        'value' => 'existing',
        'status' => 'translated',
    ]);

    $this->actingAs($this->contributor, 'translations')
        ->put(route('ltu.phrases.update', [$this->language, $key]), [
            'value' => null,
        ])
        ->assertRedirect();

    $translation = Translation::query()
        ->where('translation_key_id', $key->id)
        ->where('language_id', $this->language->id)
        ->first();

    expect($translation->translated_by)->toBeNull();
});
