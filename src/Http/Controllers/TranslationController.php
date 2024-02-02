<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Outhebox\TranslationsUI\Actions\CreateTranslationForLanguageAction;
use Outhebox\TranslationsUI\Http\Resources\LanguageResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Translation;
use Outhebox\TranslationsUI\TranslationsManager;

class TranslationController extends BaseController
{
    public function publish(): Modal
    {
        return Inertia::modal('translations/modals/publish-translations', [
            'canPublish' => (bool) Translation::count() > 0,
            'isProductionEnv' => (bool) app()->environment('production'),
        ])->baseRoute('ltu.translation.index');
    }

    public function export(): RedirectResponse
    {
        try {
            app(TranslationsManager::class)->export();

            return redirect()->route('ltu.translation.index')->with('notification', [
                'type' => 'success',
                'body' => 'Translations have been exported successfully',
            ]);
        } catch (Exception $e) {
            return redirect()->route('ltu.translation.index')->with('notification', [
                'type' => 'error',
                'body' => $e->getMessage(),
            ]);
        }
    }

    public function download()
    {
        $downloadPath = app(TranslationsManager::class)->download();

        if (! $downloadPath) {
            return redirect()->route('ltu.translation.index')->with('notification', [
                'type' => 'error',
                'body' => 'Translations could not be downloaded',
            ]);
        }

        return response()->download($downloadPath, 'lang.zip');
    }

    public function index(): Response
    {
        $translations = Translation::with('language')
            ->withCount('phrases')
            ->withProgress()
            ->get();

        $allTranslations = $translations->where('source', false);
        $sourceTranslation = $translations->firstWhere('source', true);

        return Inertia::render('translations/index', [
            'translations' => TranslationResource::collection($allTranslations),
            'sourceTranslation' => $sourceTranslation ? TranslationResource::make($sourceTranslation) : null,
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('translations/modals/add-translation', [
            'languages' => LanguageResource::collection(
                Language::whereNotIn('id', Translation::pluck('language_id')->toArray())->get()
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
            CreateTranslationForLanguageAction::execute($language);
        }

        return redirect()->route('ltu.translation.index')->with('notification', [
            'type' => 'success',
            'body' => 'Translations have been added successfully',
        ]);

    }

    public function destroy(Translation $translation): RedirectResponse
    {
        $translation->delete();

        return redirect()->route('ltu.translation.index')->with('notification', [
            'type' => 'success',
            'body' => 'Translation has been deleted successfully',
        ]);
    }

    public function destroy_multiple(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_ids' => 'required|array',
        ]);

        $selectedTranslationIds = $request->input('selected_ids');
        $translations = Translation::whereIn('id', $selectedTranslationIds)->get();

        foreach ($translations as $translation) {
            $translation->delete();
        }

        return redirect()->route('ltu.translation.index')->with('notification', [
            'type' => 'success',
            'body' => 'Selected translations have been deleted successfully',
        ]);
    }
}
