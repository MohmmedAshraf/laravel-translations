<?php

use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

use function Pest\Faker\fake;

beforeEach(function () {
    $sourceTranslation = Translation::factory([
        'source' => true,
    ])->create();

    $this->source_phrase = Phrase::factory(5)->withParameters()->create([
        'translation_id' => $sourceTranslation->id,
    ]);

    $this->translation = Translation::factory()->create();

    Phrase::factory()->create([
        'translation_id' => $this->translation->id,
    ]);

    $this->phrase = Phrase::factory()->create([
        'translation_id' => $this->translation->id,
        'phrase_id' => $this->source_phrase->first()->id,
    ]);
});

it('can render source phrases page', function () {
    $this->actingAs($this->owner, 'translations')
        ->get(route('ltu.source_translation'))
        ->assertStatus(200);
});

it('can render source phrases edit page', function () {
    $this->actingAs($this->owner, 'translations')
        ->get(route('ltu.source_translation.edit', $this->source_phrase->first()->uuid))
        ->assertStatus(200);
});

it('can update source phrase', function () {
    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.source_translation.update', $this->source_phrase->first()->uuid), [
            'note' => fake()->sentence,
            'phrase' => fake()->sentence,
            'file' => $this->source_phrase->first()->translation_file_id,
        ])->assertRedirect(route('ltu.source_translation'));
});

it('can delete source phrase', function () {
    $this->actingAs($this->owner, 'translations')
        ->delete(route('ltu.source_translation.delete_phrase', $this->source_phrase->first()->uuid))
        ->assertRedirect(route('ltu.source_translation'));
});

it('can delete multiple source phrases', function () {
    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.source_translation.delete_phrases', [
            'selected_ids' => [$this->source_phrase->pluck('id')->first()],
        ]))->assertRedirect(route('ltu.source_translation'));
});

it('can add new source key to php file', function () {
    $file = TranslationFile::factory()->create();

    $phrase = Phrase::factory()->make([
        'translation_id' => $this->translation->id,
        'translation_file_id' => $file->id,
    ]);

    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.source_translation.store_source_key'), [
            'file' => $file->id,
            'key' => $phrase->key,
            'content' => $phrase->value,
        ])->assertRedirect(route('ltu.source_translation'));
});

it('can add new source key to json file', function () {
    $file = TranslationFile::factory()->json()->create(['name' => 'en']);

    $phrase = Phrase::factory()->make([
        'key' => 'Hello :name',
        'translation_id' => $this->translation->id,
        'translation_file_id' => $file->id,
    ]);

    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.source_translation.store_source_key'), [
            'file' => $file->id,
            'key' => $phrase->key,
            'content' => $phrase->value,
        ])->assertRedirect(route('ltu.source_translation'));
});
