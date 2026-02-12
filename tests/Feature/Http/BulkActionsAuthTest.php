<?php

use Outhebox\Translations\Models\Contributor;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;

beforeEach(function () {
    useContributorAuth();
    $this->source = Language::factory()->source()->create();
});

it('allows owner to bulk delete languages', function () {
    $owner = Contributor::factory()->owner()->create();
    $language = Language::factory()->create();

    $this->actingAs($owner, 'translations')
        ->post(route('ltu.languages.bulk-action', 'delete'), [
            'ids' => [$language->id],
        ])
        ->assertRedirect();

    $this->assertDatabaseMissing('ltu_languages', ['id' => $language->id]);
});

it('skips source language in bulk delete', function () {
    $owner = Contributor::factory()->owner()->create();

    $this->actingAs($owner, 'translations')
        ->post(route('ltu.languages.bulk-action', 'delete'), [
            'ids' => [$this->source->id],
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('ltu_languages', ['id' => $this->source->id]);
});

it('denies translator from bulk deleting languages', function () {
    $translator = Contributor::factory()->translator()->create();
    $language = Language::factory()->create();

    $this->actingAs($translator, 'translations')
        ->post(route('ltu.languages.bulk-action', 'delete'), [
            'ids' => [$language->id],
        ])
        ->assertForbidden();
});

it('allows owner to bulk delete source phrases', function () {
    $owner = Contributor::factory()->owner()->create();
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();

    $this->actingAs($owner, 'translations')
        ->post(route('ltu.source.bulk-action', 'delete'), [
            'ids' => [$key->id],
        ])
        ->assertRedirect();

    $this->assertDatabaseMissing('ltu_translation_keys', ['id' => $key->id]);
});

it('denies viewer from bulk deleting source phrases', function () {
    $viewer = Contributor::factory()->viewer()->create();
    $group = Group::factory()->create();
    $key = TranslationKey::factory()->for($group)->create();

    $this->actingAs($viewer, 'translations')
        ->post(route('ltu.source.bulk-action', 'delete'), [
            'ids' => [$key->id],
        ])
        ->assertForbidden();
});
