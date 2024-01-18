<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
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
use Spatie\QueryBuilder\QueryBuilder;

class SourcePhraseController extends BaseController
{
    public function import(): RedirectResponse
    {
        try {
            Artisan::call('translations:import', [
                '--force' => true,
            ]);

            return redirect()->route('ltu.translation.index');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->withErrors([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function index(Request $request): Response
    {
        $source = Translation::where('source', true)->first();

        $phrases = QueryBuilder::for(Phrase::class)
            ->orderBy('key')
            ->allowedFilters(['key', 'value', 'status'])
            ->where('translation_id', $source->id)
            ->paginate($request->input('perPage') ?? 12)
            ->appends(request()->query());

        return Inertia::render('source/index', [
            'phrases' => PhraseResource::collection($phrases),
            'translation' => TranslationResource::make($source),
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('source/modals/add-source-key')
            ->baseRoute('ltu.source_translation');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'file' => 'required',
            'key_translation' => 'required',
        ]);

        CreateSourceKeyAction::execute(
            key: $request->input('key'),
            file: $request->input('file'),
            key_translation: $request->input('key_translation'),
        );

        return redirect()->route('ltu.source_translation');
    }

    public function edit(Phrase $phrase): Response
    {
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
