<?php

namespace Outhebox\LaravelTranslations\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Outhebox\LaravelTranslations\Http\Resources\LanguageResource;
use Outhebox\LaravelTranslations\Http\Resources\TranslationResource;
use Outhebox\LaravelTranslations\Models\Language;
use Outhebox\LaravelTranslations\Models\Translation;

class TranslationController extends BaseController
{
    public function index(Request $request): Response
    {
        $source_language = Translation::where('source', true)->withCount('phrases')->first();

        $translations_languages = Translation::orderByDesc('source')
            ->whereKeyNot($source_language?->id)
            ->when($request->has('search'), function ($query) use ($request) {
                $query->whereHas('language', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->input('search')}%")
                            ->orWhere('code', 'like', "%{$request->input('search')}%");
                    });
                });
            })
            ->get();

        return Inertia::render('index', [
            'source_language' => TranslationResource::make($source_language),
            'languages' => TranslationResource::collection($translations_languages),
        ]);
    }

    public function confirmationModal(): Modal
    {
        return Inertia::modal('modals/confirmation')
            ->baseRoute('ltu.translation.index');
    }

    public function create(): Modal
    {
        return Inertia::modal('translations/modals/add-translation', [
            'languages' => LanguageResource::collection(
                Language::whereNotIn('id', Translation::all()->pluck('language_id')->toArray())->get()
            )->toArray(request()),
        ])->baseRoute('ltu.translation.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'languages' => 'required|array',
        ]);

        $languages = Language::whereIn('id', $request->input('languages'))->get();

        foreach ($languages as $language) {
            $translation = Translation::create([
                'source' => false,
                'language_id' => $language->id,
            ]);

            $sourceTranslation = Translation::where('source', true)->first();

            foreach ($sourceTranslation->phrases()->with('file')->get() as $sourcePhrase) {
                $translation->phrases()->create([
                    'value' => null,
                    'key' => $sourcePhrase->key,
                    'group' => $sourcePhrase->group,
                    'phrase_id' => $sourcePhrase->id,
                    'parameters' => $sourcePhrase->parameters,
                    'translation_file_id' => $sourcePhrase->file->id,
                ]);
            }
        }

        return redirect()->route('ltu.translation.index');
    }

    public function destroy(Translation $translation): RedirectResponse
    {
        $translation->delete();

        return redirect()->route('ltu.translation.index');
    }
}
