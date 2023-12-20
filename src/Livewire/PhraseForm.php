<?php

namespace Outhebox\LaravelTranslations\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use WireUi\Traits\Actions;

class PhraseForm extends Component
{
    use Actions;

    public $content = '';

    public Phrase $phrase;

    public Translation $translation;

    public function mount(Translation $translation, Phrase $phrase): void
    {
        $this->phrase = $phrase;
        $this->translation = $translation;

        $this->content = $phrase?->value;
    }

    public function save(): void
    {
        if (blank($this->content)) {
            $this->notification()->error('Please enter a translation.');

            return;
        }

        if (! blank($this->phrase->source) && $this->missingParameters()) {
            $this->notification()->error('Required parameters are missing.');

            return;
        }

        $this->phrase->value = $this->content;

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
        if (is_array($this->phrase->source->parameters)) {
            foreach ($this->phrase->source->parameters as $parameter) {
                if (! str_contains($this->phrase->value, ":$parameter")) {
                    return true;
                }
            }
        }

        return false;
    }

    public function render(): View
    {
        return view('translations::livewire.phrase-form');
    }
}
