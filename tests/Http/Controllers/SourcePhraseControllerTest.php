<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;
use Outhebox\TranslationsUI\Tests\TestCase;

class SourcePhraseControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

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
    }

    /** @test */
    public function it_can_render_source_translation_page()
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.source_translation'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_can_render_source_translation_edit_page()
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.source_translation.edit', $this->source_phrase->first()->uuid))
            ->assertStatus(200);
    }

    /** @test */
    public function new_source_key_can_be_added()
    {
        $this->withoutExceptionHandling();

        $file = TranslationFile::factory()->create();

        $phrase = Phrase::factory()->make([
            'translation_id' => $this->translation->id,
            'translation_file_id' => $file->id,
        ]);

        $this->actingAs($this->owner, 'translations')
            ->post(route('ltu.source_translation.store'), [
                'file' => $file->id,
                'key' => $phrase->key,
                'key_translation' => $phrase->value,
            ])->assertRedirect(route('ltu.source_translation'));
    }

    /** @test */
    public function phrase_can_be_updated()
    {
        $this->actingAs($this->owner, 'translations')
            ->post(route('ltu.source_translation.update', $this->source_phrase->first()->uuid), [
                'note' => $this->faker->sentence,
                'phrase' => $this->faker->sentence,
                'file' => $this->source_phrase->first()->translation_file_id,
            ])->assertRedirect(route('ltu.source_translation'));
    }

    /** @test */
    public function phrase_can_be_deleted()
    {
        $this->actingAs($this->owner, 'translations')
            ->delete(route('ltu.source_translation.delete_phrase', $this->source_phrase->first()->uuid))
            ->assertRedirect(route('ltu.source_translation'));
    }

    /** @test */
    public function multiple_phrases_can_be_deleted()
    {
        $this->actingAs($this->owner, 'translations')
            ->post(route('ltu.source_translation.delete_phrases', [
                'selected_ids' => [$this->source_phrase->pluck('id')->first()],
            ]))->assertRedirect(route('ltu.source_translation'));
    }
}
