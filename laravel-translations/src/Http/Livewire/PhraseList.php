<?php

namespace Outhebox\LaravelTranslations\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;
use WireUi\Traits\Actions;

class PhraseList extends Component
{
    use Actions;
    use withPagination;

    public $search;

    public $perPage = 12;

    public Translation $translation;

    protected $listeners = [
        'sourceKeyCreated' => '$refresh',
    ];

    public function mount(Translation $translation)
    {
        $this->translation = $translation;
    }

    public function confirmDelete(Phrase $phrase)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'This action will delete the source key and all related translations, are you sure you want to continue?',
            'method' => 'delete',
            'params' => $phrase,
            'style' => 'inline',
            'icon' => 'error',
            'acceptLabel' => 'Yes, delete it',
        ]);
    }

    public function delete(Phrase $phrase)
    {
        if (! $phrase->translation->source) {
            return;
        }

        $phrase->delete();

        $this->notification()->success('Source key deleted successfully!');
    }

    public function getPhrases(): LengthAwarePaginator
    {
        return $this->translation->phrases()
            ->orderBy('key')
            ->with(['file', 'translation'])
            ->when($this->search, function ($query) {
                $query->where('key', 'like', "%$this->search%")
                    ->orWhere('value', 'like', "%$this->search%");
            })
            ->paginate($this->perPage)->onEachSide(0);
    }

    public function render(): View
    {
        return view('translations::livewire.phrase-list', [
            'phrases' => $this->getPhrases(),
        ]);
    }
}
