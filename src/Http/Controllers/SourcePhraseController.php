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

        if ($request->has('filter.keyword')) {
            $phrases->where(function (Builder $query) use ($request) {
                $query->where('key', 'LIKE', "%{$request->input('filter.keyword')}%")
                    ->orWhere('value', 'LIKE', "%{$request->input('filter.keyword')}%");
            });
        }

        $phrases = $phrases
            ->orderBy('key')
            ->paginate($request->input('perPage') ?? 12)
            ->withQueryString();

        return Inertia::render('source/index', [
            'phrases' => PhraseResource::collection($phrases),
            'translation' => TranslationResource::make($source),
            'filter' => $request->input('filter', collect()),
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('source/modals/add-source-key', [
            'files' => TranslationFileResource::collection(TranslationFile::get()),
        ])->baseRoute('ltu.source_translation');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => ['required', 'regex:/^[\w. ]+$/u'],
            'file' => ['required', 'integer', 'exists:ltu_translation_files,id'],
            'content' => ['required', 'string'],
        ]);

        CreateSourceKeyAction::execute(
            key: $request->input('key'),
            file: $request->input('file'),
            content: $request->input('content'),
        );

        return redirect()->route('ltu.source_translation');
    }

    public function edit(Phrase $phrase): Response|RedirectResponse
    {
        if (! $phrase->translation->source) {
            return redirect()->route('ltu.phrases.edit', $phrase->uuid);
        }

        return Inertia::render('source/edit', [
            'phrase' => PhraseResource::make($phrase),
            'translation' => TranslationResource::make($phrase->translation),
            'source' => TranslationResource::make($phrase->translation),
            'files' => TranslationFileResource::collection(TranslationFile::get()),
            'similarPhrases' => PhraseResource::collection($phrase->similarPhrases()),
        ]);
    }

    public function update(Phrase $phrase, Request $request): RedirectResponse
    {
        $request->validate([
            'note' => 'nullable|string',
            'phrase' => 'required|string',
            'file' => 'required|integer|exists:ltu_translation_files,id',
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
            ? redirect()->route('ltu.source_translation.edit', ['translation' => $phrase->translation, 'phrase' => $nextPhrase])
            : redirect()->route('ltu.source_translation');
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
