<?php

namespace Outhebox\LaravelTranslations\Http\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Outhebox\LaravelTranslations\Models\Translation;
use WireUi\Traits\Actions;

class TranslationsList extends Component
{
    use Actions;
    use withPagination;

    public $search;

    protected $listeners = [
        'translationCreated' => '$refresh',
    ];

    public function getTranslations(): LengthAwarePaginator
    {
        return Translation::orderByDesc('source')
            ->when($this->search, function ($query) {
                $query->whereHas('language', function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%");
                });
            })
            ->paginate(12)->onEachSide(0);
    }

    public function confirmDelete(Translation $translation)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'This action will delete the translation and all phrases, are you sure you want to continue?',
            'method' => 'delete',
            'style' => 'inline',
            'icon' => 'error',
            'params' => $translation,
            'acceptLabel' => 'Yes, delete it',
        ]);
    }

    public function delete(Translation $translation)
    {
        DB::transaction(function () use ($translation) {
            $translation->phrases()->delete();
            $translation->delete();

            $this->notification()->success('Translation deleted successfully!');
        });
    }

    public function render(): View
    {
        return view('translations::livewire.translations-list', [
            'translations' => $this->getTranslations(),
        ]);
    }
}
