<?php

namespace Outhebox\LaravelTranslations\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use WireUi\Traits\Actions;

class PhraseForm extends Component
{
    use Actions;

    public Phrase $phrase;

    public Translation $translation;

    public function mount(Translation $translation, Phrase $phrase)
    {
        $this->phrase = $phrase;
        $this->translation = $translation;
    }

    public function rules(): array
    {
        return [
            'phrase.value' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'phrase.value.required' => 'Please enter a translation.',
        ];
    }

    public function save()
    {
        $this->validate();

        if (! blank($this->phrase->source) && $this->missingParameters()) {
            $this->notification()->error('Required parameters are missing.');

            return;
        }

        $this->phrase->save();

        $this->notification()->success('Phrase updated successfully!');

        $nextPhrase = $this->translation->phrases()
            ->where('id', '>', $this->phrase->id)
            ->whereNull('value')
            ->first();

        if ($nextPhrase) {
            $this->redirect(route('translations_ui.phrases.show', [
                'phrase' => $nextPhrase,
                'translation' => $this->translation,
            ]));

            return;
        }

        $this->redirect(route('translations_ui.phrases.index', $this->translation));
    }

    public function missingParameters(): bool
    {
        foreach ($this->phrase->source->parameters as $parameter) {
            if (! str_contains($this->phrase->value, ":$parameter")) {
                return true;
            }
        }

        return false;
    }

    public function render(): View
    {
        return view('translations::livewire.phrase-form');
    }
}
