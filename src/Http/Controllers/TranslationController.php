<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\TranslationsUI\Actions\CreateTranslationForLanguageAction;
use Outhebox\TranslationsUI\Http\Resources\LanguageResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Translation;

class TranslationController extends BaseController
{
    public function index(): Response
    {
        return Inertia::render('Translations/Index', [
            'languages' => LanguageResource::collection(Language::whereNotIn('id', Translation::pluck('language_id'))
                ->orderBy('name')
                ->get()),
            'translations' => TranslationResource::collection(Translation::with('language')
                ->withCount('phrases')
                ->withProgress()
                ->get()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'language_ids' => ['required', 'array'],
        ], [
            'language_ids.required' => 'Please select at least one language.',
        ]);

        DB::transaction(function () use ($request) {
            $languages = Language::whereIn('id', $request->input('language_ids'))->get();

            foreach ($languages as $language) {
                CreateTranslationForLanguageAction::execute($language);
            }
        });

        return redirect()->route('ltu.translation.index');
    }

    public function destroy(Translation $translation): JsonResponse
    {
        $translation->delete();

        return response()->json([
            'message' => 'Translation deleted successfully.',
        ]);
    }

    public function destroy_multiple(Request $request): JsonResponse
    {
        $request->validate([
            'selected_ids' => 'required|array',
        ]);

        $selectedTranslationIds = $request->input('selected_ids');
        $translations = Translation::whereIn('id', $selectedTranslationIds)->get();

        foreach ($translations as $translation) {
            $translation->delete();
        }

        return response()->json([
            'message' => 'Translations deleted successfully.',
        ]);
    }
}
