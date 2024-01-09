<?php

namespace Outhebox\LaravelTranslations\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Outhebox\LaravelTranslations\Http\Resources\PhraseResource;
use Outhebox\LaravelTranslations\Http\Resources\TranslationResource;
use Outhebox\LaravelTranslations\Models\Phrase;
use Outhebox\LaravelTranslations\Models\Translation;

class SourceTranslationController extends BaseController
{
    public function index(Request $request): Response
    {
        $source_language = Translation::where('source', true)->first();

        return Inertia::render('source/index', [
            'phrases' => PhraseResource::collection($source_language->phrases()
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

            'translation' => TranslationResource::make($source_language),
        ]);
    }

    public function edit(Phrase $phrase): Response
    {
        return Inertia::render('source/edit', [
            'phrase' => PhraseResource::make($phrase),
            'translation' => TranslationResource::make($phrase->translation),
            'source' => TranslationResource::make(Translation::where('source', true)?->first()),
        ]);
    }

    public function update(Phrase $phrase, Request $request): RedirectResponse
    {
        $request->validate([
            'updatePhrase' => 'required|string',
        ]);

        $phrase->update([
            'value' => $request->input('updatePhrase'),
        ]);

        $nextPhrase = $phrase->translation->phrases()
            ->where('id', '>', $phrase->id)
            ->whereNull('value')
            ->first();

        if ($nextPhrase) {
            return redirect()->route('ltu.phrases.edit', [
                'translation' => $phrase->translation,
                'phrase' => $nextPhrase,
            ]);
        }

        return redirect()->route('ltu.translation.source_language');
    }

    public function create(): Modal
    {
        return Inertia::modal('source/modals/add-source-key')
            ->baseRoute('ltu.translation.source_language');
    }
}
