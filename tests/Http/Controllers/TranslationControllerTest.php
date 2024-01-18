<?php

namespace Outhebox\TranslationsUI\Tests\Http\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Tests\TestCase;

class TranslationControllerTest extends TestCase
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
    }

    /** @test */
    public function it_can_render_translations_page()
    {
        $this->actingAs($this->owner, 'translations')
            ->get(route('ltu.translation.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function it_can_store_new_translation()
    {
        $this->actingAs($this->owner, 'translations')
            ->post(route('ltu.translation.store'), [
                'languages' => [Language::inRandomOrder()->first()->id],
            ])
            ->assertRedirect(route('ltu.translation.index'));

        $this->assertCount(3, Translation::all());
    }

    /** @test */
    public function translation_can_be_deleted()
    {
        $this->actingAs($this->owner, 'translations')
            ->delete(route('ltu.translation.delete', $this->translation->id))
            ->assertRedirect(route('ltu.translation.index'));

        $this->assertCount(1, Translation::all());
    }

    /** @test */
    public function multiple_translations_can_be_deleted()
    {
        $this->actingAs($this->owner, 'translations')
            ->post(route('ltu.translation.delete_multiple', [
                'selected_ids' => [$this->translation->id],
            ]))
            ->assertRedirect(route('ltu.translation.index'));

        $this->assertCount(1, Translation::all());
    }
}
