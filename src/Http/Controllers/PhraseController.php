<?php

namespace Outhebox\LaravelTranslations\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\LaravelTranslations\Http\Resources\PhraseResource;
use Outhebox\LaravelTranslations\Http\Resources\TranslationResource;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;

class PhraseController extends BaseController
{
    public function index(Translation $translation, Request $request): Response
    {
        return Inertia::render('translations/index', [
            'phrases' => PhraseResource::collection($translation->phrases()
                ->orderBy('key')
                ->with(['file', 'translation'])
                ->when($request->has('search'), function (Builder $query) use ($request) {
                    $query->where(function (Builder $query) use ($request) {
                        $query->where('key', 'like', "%{$request->input('search')}%")
                            ->orWhere('value', 'like', "%{$request->input('search')}%");
                    });
                })
                ->when($request->has('status'), function (Builder $query) use ($request) {
                    $query->where(function (Builder $query) use ($request) {
                        $request->input('status') === 'translated'
                            ? $query->whereNotNull('value')
                            : $query->whereNull('value');
                    });
                })
                ->paginate($request->input('perPage') ?? 12)->withQueryString()),

            'translation' => TranslationResource::make($translation),
        ]);
    }

    public function edit(Translation $translation, Phrase $phrase): Response
    {
        return Inertia::render('translations/edit', [
            'phrase' => PhraseResource::make($phrase),
            'translation' => TranslationResource::make($translation),
            'source' => TranslationResource::make(Translation::where('source', true)?->first()),
        ]);
    }

    public function update(Translation $translation, Phrase $phrase, Request $request): RedirectResponse
    {
        $request->validate([
            'updatePhrase' => 'required|string',
        ]);

        if (! $translation->source) {
            if (is_array($phrase->source->parameters)) {
                foreach ($phrase->source->parameters as $parameter) {
                    if (! str_contains($request->input('updatePhrase'), ":$parameter")) {
                        return redirect()->back()->withErrors([
                            'updatePhrase' => 'Required parameters are missing.',
                        ]);
                    }
                }
            }
        }

        $phrase->update([
            'value' => $request->input('updatePhrase'),
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
