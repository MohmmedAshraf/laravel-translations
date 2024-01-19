<?php

use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;

use function Pest\Faker\fake;

beforeEach(function () {
    $sourceTranslation = Translation::factory([
        'source' => true,
    ])->create();

    $SourcePhrase = Phrase::factory(5)->withParameters()->create([
        'translation_id' => $sourceTranslation->id,
    ]);

    $this->translation = Translation::factory()->create();

    Phrase::factory()->create([
        'translation_id' => $this->translation->id,
    ]);

    $this->phrase = Phrase::factory()->create([
        'translation_id' => $this->translation->id,
        'phrase_id' => $SourcePhrase->first()->id,
    ]);
});

it('can render phrases page', function () {
    $this->actingAs($this->owner, 'translations')
        ->get(route('ltu.phrases.index', $this->translation))
        ->assertStatus(200);
});

it('can update phrase', function () {
    $this->actingAs($this->owner, 'translations')
        ->post(route('ltu.phrases.update', [
            'phrase' => $this->phrase->uuid,
            'translation' => $this->translation->id,
        ]), [
            'phrase' => fake()->sentence(),
        ])
        ->assertStatus(302);
});
