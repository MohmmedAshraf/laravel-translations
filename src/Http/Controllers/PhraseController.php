<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\TranslationsUI\Http\Resources\PhraseResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Spatie\QueryBuilder\QueryBuilder;
use Stichoza\GoogleTranslate\Exceptions\LargeTextException;
use Stichoza\GoogleTranslate\Exceptions\RateLimitException;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PhraseController extends BaseController
{
    public function index(Translation $translation, Request $request): Response
    {
        $phrases = QueryBuilder::for(Phrase::class)
            ->orderBy('key')
            ->allowedFilters(['key', 'value', 'status'])
            ->where('translation_id', $translation->id)
            ->paginate($request->input('perPage') ?? 12)
            ->appends(request()->query());

        return Inertia::render('phrases/index', [
            'phrases' => PhraseResource::collection($phrases),
            'translation' => TranslationResource::make($translation),
        ]);
    }

    /**
     * @throws LargeTextException
     * @throws RateLimitException
     * @throws TranslationRequestException
     */
    public function edit(Translation $translation, Phrase $phrase): Response
    {
        return Inertia::render('phrases/edit', [
            'phrase' => PhraseResource::make($phrase),
            'translation' => TranslationResource::make($translation),
            'source' => TranslationResource::make(Translation::where('source', true)?->first()),
            'similarPhrases' => PhraseResource::collection($phrase->similarPhrases()),
            'suggestedTranslations' => [
                'google' => [
                    'id' => 'google',
                    'engine' => 'Google Translate',
                    'value' => (new GoogleTranslate())->preserveParameters()
                        ->setSource($phrase->source->translation->language->code)
                        ->setTarget($translation->language->code)
                        ->translate($phrase->source->value),
                ],
                // 'deepl' => 'DeepL',
                // 'microsoft' => 'Microsoft Translator',
                // 'amazon' => 'Amazon Translate',
            ],
        ]);
    }

    public function update(Translation $translation, Phrase $phrase, Request $request): RedirectResponse
    {
        $request->validate([
            'phrase' => 'required|string',
        ]);

        if (! $translation->source) {
            if (is_array($phrase->source->parameters)) {
                foreach ($phrase->source->parameters as $parameter) {
                    if (! str_contains($request->input('phrase'), ":$parameter")) {
                        return redirect()->back()->withErrors([
                            'phrase' => 'Required parameters are missing.',
                        ]);
                    }
                }
            }
        }

        $phrase->update([
            'value' => $request->input('phrase'),
        ]);

        $nextPhrase = $translation->phrases()
            ->where('id', '>', $phrase->id)
            ->whereNull('value')
            ->first();

        if ($nextPhrase) {
            return redirect()->route('ltu.phrases.edit', [
                'translation' => $translation,
                'phrase' => $nextPhrase,
            ]);
        }

        return redirect()->route('ltu.phrases.index', $translation);
    }
}
