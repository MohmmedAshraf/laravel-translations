<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Outhebox\TranslationsUI\Actions\CreateSourceKeyAction;
use Outhebox\TranslationsUI\Http\Resources\PhraseResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationFileResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;

class SourcePhraseController extends BaseController
{
    public function index(Request $request): Response
    {
        $source = Translation::where('source', true)->first();

        $phrases = $source->phrases()->newQuery();

        $files = [];
        foreach (collect($phrases->where('translation_id', $source->id)->get())->unique('translation_file_id') as $value) {
            $files[] = TranslationFile::where('id', $value->translation_file_id)->first();
        }

        if ($request->has('filter.keyword')) {
            $phrases->where(function (Builder $query) use ($request) {
                $query->where('key', 'LIKE', "%{$request->input('filter.keyword')}%")
                    ->orWhere('value', 'LIKE', "%{$request->input('filter.keyword')}%");
            });
        }

        if ($request->has('filter.translationFile')) {
            $phrases->where(
                ! is_null($request->input('filter.translationFile')) || ! empty($request->input('filter.translationFile'))
                    ? fn (Builder $query) => $query->where('translation_file_id', $request->input('filter.translationFile'))
                    : fn (Builder $query) => $query->whereNull('translation_file_id')
            );
        }

        $phrases = $phrases
            ->orderBy('key')
            ->paginate($request->input('perPage') ?? 12)
            ->withQueryString();

        return Inertia::render('source/index', [
            'phrases' => PhraseResource::collection($phrases),
            'translation' => TranslationResource::make($source),
            'files' => TranslationFileResource::collection(collect($files)),
            'filter' => $request->input('filter', collect()),
        ]);
    }

    public function create(): Modal
    {
        $source = Translation::where('source', true)->first();

        $phrases = $source->phrases()->newQuery();

        $files = [];
        foreach (collect($phrases->where('translation_id', $source->id)->get())->unique('translation_file_id') as $value) {
            $files[] = TranslationFile::where('id', $value->translation_file_id)->first();
        }

        return Inertia::modal('source/modals/add-source-key', [
            'files' => TranslationFileResource::collection(collect($files)),
        ])->baseRoute('ltu.source_translation');
    }

    public function store(Request $request): RedirectResponse
    {
        $connection = config('translations.database_connection');

        $key = ['required', 'regex:/^[\w.]+$/u'];
        if (TranslationFile::find($request->input('file'))?->extension === 'json') {
            $key = ['required', 'string'];
        }

        $request->validate([
            'key' => $key,
            'file' => ['required', 'integer', 'exists:'.($connection ? $connection.'.' : '').'ltu_translation_files,id'],
            'content' => ['required', 'string'],
        ]);

        CreateSourceKeyAction::execute(
            key: $request->input('key'),
            file: $request->input('file'),
            content: $request->input('content'),
        );

        return redirect()->route('ltu.source_translation')->with('notification', [
            'type' => 'success',
            'body' => 'Phrase has been added successfully',
        ]);
    }

    public function edit(Phrase $phrase): Response|RedirectResponse
    {
        if (! $phrase->translation->source) {
            return redirect()->route('ltu.phrases.edit', $phrase->uuid);
        }

        $files = [];
        foreach (collect($phrase->where('translation_id', $phrase->translation->id)->get())->unique('translation_file_id') as $value) {
            $files[] = TranslationFile::where('id', $value->translation_file_id)->first();
        }

        return Inertia::render('source/edit', [
            'phrase' => PhraseResource::make($phrase),
            'translation' => TranslationResource::make($phrase->translation),
            'source' => TranslationResource::make($phrase->translation),
            'files' => TranslationFileResource::collection($files),
            'similarPhrases' => PhraseResource::collection($phrase->similarPhrases()),
        ]);
    }

    public function update(Phrase $phrase, Request $request): RedirectResponse
    {
        $connection = config('translations.database_connection');
        $request->validate([
            'note' => 'nullable|string',
            'phrase' => 'required|string',
            'file' => 'required|integer|exists:'.($connection ? $connection.'.' : '').'ltu_translation_files,id',
        ]);

        $phrase->update([
            'value' => $request->input('phrase'),
            'note' => $request->input('note'),
            'translation_file_id' => $request->input('file'),
            'parameters' => getPhraseParameters($request->input('phrase')),
        ]);

        $nextPhrase = $phrase->translation->phrases()
            ->where('id', '>', $phrase->id)
            ->whereNull('value')
            ->first();

        return $nextPhrase
            ? redirect()->route('ltu.source_translation.edit', ['translation' => $phrase->translation, 'phrase' => $nextPhrase])->with('notification', [
                'type' => 'success',
                'body' => 'Phrase has been updated successfully',
            ])
            : redirect()->route('ltu.source_translation')->with('notification', [
                'type' => 'success',
                'body' => 'Phrase has been updated successfully',
            ]);
    }

    public function destroy(Phrase $phrase): RedirectResponse
    {
        $phrase->delete();

        return redirect()->route('ltu.source_translation')->with('notification', [
            'type' => 'success',
            'body' => 'Phrase has been deleted successfully',
        ]);
    }

    public function destroy_multiple(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_ids' => 'required|array',
        ]);

        Phrase::whereIn('id', $request->input('selected_ids'))->delete();

        return redirect()->route('ltu.source_translation')->with('notification', [
            'type' => 'success',
            'body' => 'Selected phrases have been deleted successfully',
        ]);
    }
}
