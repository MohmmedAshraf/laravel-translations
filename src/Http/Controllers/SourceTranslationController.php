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
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Spatie\QueryBuilder\QueryBuilder;

class SourceTranslationController extends BaseController
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
            'similarPhrases' => PhraseResource::collection($phrase->similarPhrases()),
        ]);
    }

    public function update(Phrase $phrase, Request $request): RedirectResponse
    {
        $request->validate([
            'phrase' => 'required|string',
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $phrase->update([
            'value' => $request->input('phrase'),
        ]);

        $nextPhrase = $phrase->translation->phrases()
            ->where('id', '>', $phrase->id)
            ->whereNull('value')
            ->first();

        return $nextPhrase
            ? redirect()->route('ltu.phrases.edit', ['translation' => $phrase->translation, 'phrase' => $nextPhrase])
            : redirect()->route('ltu.source_translation');
    }
}
