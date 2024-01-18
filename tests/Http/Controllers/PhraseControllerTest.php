<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Tests\TestCase;

class PhraseControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

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
    }

    /** @test */
    public function it_can_render_phrases_page()
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.phrases.index', $this->translation))
            ->assertStatus(200);
    }

    /** @test */
    public function edit_phrase_page_can_be_rendered()
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.phrases.edit', [
                'phrase' => $this->phrase->uuid,
                'translation' => $this->translation->id,
            ]))
            ->assertStatus(200);
    }

    /** @test */
    public function phrase_can_be_updated()
    {
        $this->actingAs($this->owner, 'translations')
            ->post(route('ltu.phrases.update', [
                'phrase' => $this->phrase->uuid,
                'translation' => $this->translation->id,
            ]), [
                'phrase' => $this->faker->sentence(),
            ])
            ->assertStatus(302);
    }
}