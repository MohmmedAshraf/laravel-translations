<?php

namespace Outhebox\LaravelTranslations\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Outhebox\LaravelTranslations\Models\Phrase;

class SourcePhrase extends Component
{
    public Phrase $phrase;

    public function mount(Phrase $phrase): void
    {
        $this->phrase = $phrase;
    }

    public function render(): View
    {
        return view('translations::livewire.source-phrase');
    }
}
