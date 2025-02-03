<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\TranslationsUI\Http\Resources\PhraseResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationFileResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\Models\TranslationFile;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Stichoza\GoogleTranslate\Exceptions\LargeTextException;
use Stichoza\GoogleTranslate\Exceptions\RateLimitException;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PhraseController extends BaseController
{
    public function index(Translation $translation, Request $request): Response|RedirectResponse
    {
        if ($translation->source) {
            return redirect()->route('ltu.source_translation');
        }

        $phrases = QueryBuilder::for($translation->phrases())
            ->allowedFilters([
                AllowedFilter::partial('key'),
                AllowedFilter::partial('value'),
                AllowedFilter::exact('translation_file_id'),
                AllowedFilter::callback('status', function ($query, $value) {
                    if ($value === 'translated') {
                        $query->where('value', '!=', null);
                    } else {
                        $query->where('value', '=', null);
                    }
                }),
            ])
            ->orderBy('key')
            ->paginate($request->input('per_page', 11))
            ->withQueryString();

        return Inertia::render('Translations/Language/Index', [
            'phrases' => PhraseResource::collection($phrases),
            'translation' => TranslationResource::make($translation),
            'files' => TranslationFileResource::collection(TranslationFile::distinct()->get()),
            'filter' => $request->input('filter', collect()),
        ]);
    }

    /**
     * @throws LargeTextException
     * @throws RateLimitException
     * @throws TranslationRequestException
     */
    public function edit(Translation $translation, Phrase $phrase): RedirectResponse|Response
    {
        if ($phrase->translation->source) {
            return redirect()->route('ltu.source_translation.edit', $phrase->uuid);
        }

        return Inertia::render('Translations/Phrase/Form', [
            'phrase' => PhraseResource::make($phrase),
            'translation' => TranslationResource::make($translation),
            'source' => TranslationResource::make(Translation::where('source', true)?->first()),
            'similarPhrases' => PhraseResource::collection($phrase->similarPhrases()),
            'suggestedTranslations' => [
                [
                    'id' => 'google',
                    'engine' => 'Google Translate',
                    'value' => (new GoogleTranslate)->preserveParameters()
                        ->setSource($phrase->source->translation->language->code)
                        ->setTarget($translation->language->code)
                        ->translate($phrase->source->value),
                ],
            ],
        ]);
    }

    public function update(Translation $translation, Phrase $phrase, Request $request): RedirectResponse
    {
        $request->validate([
            'phrase' => 'required|string',
        ], [
            'phrase.required' => 'Invalid empty translation',
        ]);

        if (! $translation->source) {
            if (is_array($phrase->source->parameters)) {
                foreach ($phrase->source->parameters as $parameter) {
                    if (! str_contains($request->input('phrase'), ":$parameter")) {
                        return redirect()->back()->withErrors([
                            'phrase' => "Missing placeholder :$parameter in translation",
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
