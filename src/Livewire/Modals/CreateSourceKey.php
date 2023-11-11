<?php

namespace Outhebox\LaravelTranslations\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Outhebox\LaravelTranslations\Models\Translation;
use Outhebox\LaravelTranslations\Models\TranslationFile;
use WireUi\Traits\Actions;

class CreateSourceKey extends ModalComponent
{
    use Actions;

    public $key;

    public $file;

    public $key_translation;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function rules(): array
    {
        return [
            'key' => 'required',
            'file' => 'required',
            'key_translation' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'Please enter a key.',
            'file.required' => 'Please select a file.',
            'key_translation.required' => 'Please enter a translation for this key.',
        ];
    }

    public function create(): void
    {
        $this->validate();

        $sourceTranslation = Translation::where('source', true)->first();

        $sourceKey = $sourceTranslation->phrases()->create([
            'key' => $this->key,
            'phrase_id' => null,
            'parameters' => null,
            'value' => $this->key_translation,
            'translation_file_id' => $this->file,
            'group' => TranslationFile::find($this->file)->name,
        ]);

        foreach (Translation::where('source', false)->get() as $translation) {
            $translation->phrases()->create([
                'value' => null,
                'key' => $sourceKey->key,
                'group' => $sourceKey->group,
                'phrase_id' => $sourceKey->id,
                'parameters' => $sourceKey->parameters,
                'translation_file_id' => $sourceKey->file->id,
            ]);
        }

        $this->dispatch('sourceKeyCreated');

        $this->notification()->success('Source key created successfully.');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('translations::livewire.modals.create-source-key', [
            'files' => TranslationFile::all(),
        ]);
    }
}
