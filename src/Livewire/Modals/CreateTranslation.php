<?php

namespace Outhebox\LaravelTranslations\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Translation;
use WireUi\Traits\Actions;

class CreateTranslation extends ModalComponent
{
    use Actions;

    public $language;

    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function rules(): array
    {
        return [
            'language' => 'required|exists:ltu_languages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'language.required' => 'Please select a language.',
            'language.exists' => 'The selected language does not exist.',
        ];
    }

    public function create(): void
    {
        $this->validate();

        $translation = Translation::create([
            'source' => false,
            'language_id' => $this->language,
        ]);

        $sourceTranslation = Translation::where('source', true)->first();

        foreach ($sourceTranslation->phrases()->with('file')->get() as $sourcePhrase) {
            $translation->phrases()->create([
                'value' => null,
                'key' => $sourcePhrase->key,
                'group' => $sourcePhrase->group,
                'phrase_id' => $sourcePhrase->id,
                'parameters' => $sourcePhrase->parameters,
                'translation_file_id' => $sourcePhrase->file->id,
            ]);
        }

        $this->dispatch('translationCreated', $translation->id);

        $this->notification()->success('Translation created successfully.');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('translations::livewire.modals.create-translation', [
            'languages' => Language::whereNotIn('id', Translation::pluck('language_id'))->get(),
        ]);
    }
}
