<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Concerns\HasDataTable;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Http\Requests\UpdatePhraseRequest;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Rules\TranslationParametersRule;
use Outhebox\Translations\Services\TranslationAuth;
use Outhebox\Translations\Support\DataTable\Column;
use Outhebox\Translations\Support\DataTable\Filter;

class PhraseController extends Controller
{
    use HasDataTable;

    private ?Language $language = null;

    private ?Language $sourceLanguage = null;

    private function auth(): TranslationAuth
    {
        return app(TranslationAuth::class);
    }

    protected function tableModel(): string
    {
        return TranslationKey::class;
    }

    protected function tableColumns(): array
    {
        return [
            Column::make('status', 'Status')->statusIcon(TranslationStatus::class)->headerIcon('check-circle')->maxSize(50),
            Column::make('key', 'Key')->mono()->searchable()->sortable()->fixed()->maxSize(400),
            Column::make('group_name', 'Group')->maxSize(160),
            Column::make('source_value', 'Source')->truncate(60)->maxSize(300),
            Column::make('translation_value', 'Translation')->fill()->truncate(80),
        ];
    }

    protected function tableFilters(): array
    {
        return [
            Filter::make('status', TranslationStatus::class)->label('Status')->icon('check-circle'),
            Filter::make('group_id')->label('Group')->icon('folder')
                ->fromModel(Group::query()->orderBy('name'), fn (Group $group) => $group->displayName()),
            Filter::make('missing_params')->label('Missing Params')->icon('alert-triangle')
                ->options([
                    ['value' => '1', 'label' => 'Missing params'],
                ]),
        ];
    }

    protected function tableFilterCallbacks(): array
    {
        return [
            'status' => function (Builder $query, $value): void {
                $query->whereHas('translations', function ($q) use ($value): void {
                    $q->where('language_id', $this->language->id)
                        ->where('status', $value);
                });
            },
            'group_id' => fn (Builder $query, $value) => $query->where('group_id', (int) $value),
            'missing_params' => function (Builder $query): void {
                $languageId = $this->language->id;
                $ids = collect();

                TranslationKey::query()
                    ->whereNotNull('parameters')
                    ->whereJsonLength('parameters', '>', 0)
                    ->with(['translations' => fn ($tq) => $tq->where('language_id', $languageId)])
                    ->chunkById(500, function ($keys) use (&$ids): void {
                        $filtered = $keys->filter(function (TranslationKey $key): bool {
                            $value = $key->translations->first()?->value;

                            return ! $value || TranslationParametersRule::findMissing($key, $value) !== [];
                        });
                        $ids = $ids->merge($filtered->pluck('id'));
                    });

                $query->whereIn('ltu_translation_keys.id', $ids);
            },
        ];
    }

    protected function tableRelations(): array
    {
        return ['group'];
    }

    protected function modifyQuery(Builder $query): Builder
    {
        if ($this->language) {
            $query->with(['translations' => fn ($q) => $q->where('language_id', $this->language->id)]);
        }

        return $query;
    }

    protected function tableDefaultSort(): string
    {
        return 'key';
    }

    protected function resourceName(): array
    {
        return ['phrase', 'phrases'];
    }

    public function index(Request $request, Language $language): Response
    {
        abort_if($language->isSource(), 404);
        abort_unless($this->auth()->canAccessLanguage($language->id), 403);

        return Inertia::render('translations/phrases/index', $this->buildIndexData($request, $language));
    }

    protected function buildIndexData(Request $request, Language $language): array
    {
        $this->language = $language;
        $this->sourceLanguage = Language::source();

        $tableData = $this->paginatedTableData($request);

        $keyIds = collect($tableData['data'])->pluck('id')->all();

        $sourceTranslations = $this->sourceLanguage
            ? Translation::query()
                ->whereIn('translation_key_id', $keyIds)
                ->where('language_id', $this->sourceLanguage->id)
                ->pluck('value', 'translation_key_id')
            : collect();

        $tableData['data'] = collect($tableData['data'])
            ->map(function ($item) use ($sourceTranslations) {
                $data = $item instanceof TranslationKey ? $item->toArray() : (array) $item;
                $groupName = $data['group']['name'] ?? '';
                $translation = $data['translations'][0] ?? null;

                return [
                    ...$data,
                    'key' => $groupName.'.'.$data['key'],
                    'source_value' => $sourceTranslations[$data['id']] ?? null,
                    'translation_value' => $translation['value'] ?? null,
                    'status' => $translation['status'] ?? 'untranslated',
                    'group_name' => $groupName ?: null,
                ];
            })
            ->all();

        return [
            'data' => $tableData,
            'tableConfig' => $this->tableConfig($request),
            'language' => $language,
            'sourceLanguage' => $this->sourceLanguage,
        ];
    }

    public function edit(Language $language, TranslationKey $translationKey): Response
    {
        abort_if($language->isSource(), 404);
        abort_unless($this->auth()->canAccessLanguage($language->id), 403);

        return Inertia::render('translations/phrases/edit', $this->buildEditData($language, $translationKey));
    }

    protected function buildEditData(Language $language, TranslationKey $translationKey): array
    {
        $sourceLanguage = Language::source();

        $translationKey->load(['group', 'translations' => fn ($q) => $q->where('language_id', $language->id)]);

        $sourceTranslation = $this->loadSourceTranslation($translationKey, $sourceLanguage);

        return [
            'language' => $language,
            'sourceLanguage' => $sourceLanguage,
            'sourceTranslation' => $sourceTranslation,
            'translationKey' => $translationKey,
            'translationIsEmpty' => ! $translationKey->translations->first()?->value,
            'previousKey' => TranslationKey::query()->where('group_id', $translationKey->group_id)->where('key', '<', $translationKey->key)->orderByDesc('key')->value('id'),
            'nextKey' => TranslationKey::query()->where('group_id', $translationKey->group_id)->where('key', '>', $translationKey->key)->orderBy('key')->value('id'),
            'workflow' => $this->buildWorkflowData(),
            'similarKeys' => $this->buildSimilarKeys($translationKey, $language, $sourceLanguage),
        ];
    }

    public function update(UpdatePhraseRequest $request, Language $language, TranslationKey $translationKey): RedirectResponse
    {
        abort_unless($this->auth()->canAccessLanguage($language->id), 403);

        $validated = $request->validated();
        $hasValue = array_key_exists('value', $validated) && $validated['value'] !== null;

        if ($hasValue) {
            $validated['translated_by'] = $this->auth()->id();

            if (! isset($validated['status'])) {
                $validated['status'] = $this->resolveStatusForSave();
            }
        }

        Translation::query()->updateOrCreate(
            ['translation_key_id' => $translationKey->id, 'language_id' => $language->id],
            $validated,
        );

        return redirect()->back()->with('success', 'Translation updated.');
    }

    protected function loadSourceTranslation(TranslationKey $translationKey, ?Language $sourceLanguage): ?Translation
    {
        if (! $sourceLanguage) {
            return null;
        }

        return Translation::query()
            ->where('translation_key_id', $translationKey->id)
            ->where('language_id', $sourceLanguage->id)
            ->first();
    }

    protected function resolveStatusForSave(): TranslationStatus
    {
        if (! config('translations.approval_workflow', true)) {
            return TranslationStatus::Translated;
        }

        $role = $this->auth()->role();

        if ($role?->canApproveTranslations()) {
            return TranslationStatus::Approved;
        }

        return TranslationStatus::NeedsReview;
    }

    protected function buildSimilarKeys(TranslationKey $translationKey, Language $language, ?Language $sourceLanguage): array
    {
        $prefix = Str::before($translationKey->key, '_');
        if ($prefix === $translationKey->key) {
            $prefix = Str::before($translationKey->key, '.');
        }

        if (! $prefix || mb_strlen($prefix) < 2) {
            return [];
        }

        $languageIds = collect([$language->id, $sourceLanguage?->id])->filter()->values()->all();

        $similarKeys = TranslationKey::query()
            ->where('id', '!=', $translationKey->id)
            ->where('group_id', $translationKey->group_id)
            ->where('key', 'like', $prefix.'%')
            ->with(['group', 'translations' => fn ($q) => $q->whereIn('language_id', $languageIds)])
            ->limit(20)
            ->get();

        return $similarKeys->map(function (TranslationKey $key) use ($language, $sourceLanguage) {
            $groupName = $key->group?->name ?? '';

            return [
                'id' => $key->id,
                'key' => $groupName ? $groupName.'.'.$key->key : $key->key,
                'source' => $sourceLanguage
                    ? $key->translations->firstWhere('language_id', $sourceLanguage->id)?->value
                    : null,
                'translation' => $key->translations->firstWhere('language_id', $language->id)?->value,
            ];
        })->values()->all();
    }

    protected function buildWorkflowData(): array
    {
        $role = $this->auth()->role();

        return [
            'enabled' => (bool) config('translations.approval_workflow', true),
            'canApprove' => $role?->canApproveTranslations() ?? false,
            'canEdit' => $role?->canEditTranslations() ?? false,
            'saveStatus' => $this->resolveStatusForSave()->value,
        ];
    }
}
