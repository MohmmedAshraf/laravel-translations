<?php

namespace Outhebox\LaravelTranslations\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Outhebox\LaravelTranslations\Models\Phrase;

class SourcePhrase extends Component
{
    public Phrase $phrase;

    public function mount(Phrase $phrase)
    {
        $this->phrase = $phrase;
    }

    public function render(): View
    {
        return view('translations::livewire.source-phrase');
    }
}
