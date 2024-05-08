<?php

namespace Outhebox\TranslationsUI\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Outhebox\TranslationsUI\Actions\CreateTranslationForLanguageAction;
use Outhebox\TranslationsUI\Facades\TranslationsUI;
use Outhebox\TranslationsUI\Http\Requests\TranslationDestroyMultipleRequest;
use Outhebox\TranslationsUI\Http\Requests\TranslationRequest;
use Outhebox\TranslationsUI\Http\Resources\LanguageResource;
use Outhebox\TranslationsUI\Http\Resources\TranslationResource;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Translation;

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
            TranslationsUI::export();

            return redirect()->route('ltu.translation.index')->with('notification', [
                'type' => 'success',
                'body' => ltu_trans('Translations have been exported successfully'),
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
        $downloadPath = TranslationsUI::download();

        if (! $downloadPath) {
            return redirect()->route('ltu.translation.index')->with('notification', [
                'type' => 'error',
                'body' => ltu_trans('Translations could not be downloaded'),
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

    public function store(TranslationRequest $request): RedirectResponse
    {
        $request->validated();

        $languages = Language::whereIn('id', $request->input('languages'))->get();

        foreach ($languages as $language) {
            CreateTranslationForLanguageAction::execute($language);
        }

        return redirect()->route('ltu.translation.index')->with('notification', [
            'type' => 'success',
            'body' => ltu_trans('Translations have been added successfully'),
        ]);
    }

    public function destroy(Translation $translation): RedirectResponse
    {
        $translation->delete();

        return redirect()->route('ltu.translation.index')->with('notification', [
            'type' => 'success',
            'body' => ltu_trans('Translation has been deleted successfully'),
        ]);
    }

    public function destroy_multiple(TranslationDestroyMultipleRequest $request): RedirectResponse
    {
        $request->validated();

        $selectedTranslationIds = $request->input('selected_ids');
        $translations = Translation::whereIn('id', $selectedTranslationIds)->get();

        foreach ($translations as $translation) {
            $translation->delete();
        }

        return redirect()->route('ltu.translation.index')->with('notification', [
            'type' => 'success',
            'body' => ltu_trans('Selected translations have been deleted successfully'),
        ]);
    }
}
