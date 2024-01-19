<?php

use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;

beforeEach(function () {
    $sourceTranslation = Translation::factory([
        'source' => true,
    ])->create();

    $this->source_phrase = Phrase::factory(5)->withParameters()->create([
        'translation_id' => $sourceTranslation->id,
    ]);

    $this->translation = Translation::factory()->create();
});

it('can render translations page', function () {
    $this->actingAs($this->owner, 'translations')
        ->get(route('ltu.translation.index'))
        ->assertStatus(200);
});

it('can store new translation', function () {
    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.translation.store'), [
            'languages' => [Language::inRandomOrder()->first()->id],
        ])
        ->assertRedirect(route('ltu.translation.index'));

    $this->assertCount(3, Translation::all());
});

it('translation can be deleted', function () {
    $this->actingAs($this->owner, 'translations')
        ->delete(route('ltu.translation.delete', $this->translation->id))
        ->assertRedirect(route('ltu.translation.index'));

    $this->assertCount(1, Translation::all());
});

it('multiple translations can be deleted', function () {
    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.translation.delete_multiple', [
            'selected_ids' => [$this->translation->id],
        ]))
        ->assertRedirect(route('ltu.translation.index'));

    $this->assertCount(1, Translation::all());
});
