<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Concerns\HasDataTable;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\TranslationKeyCreated;
use Outhebox\Translations\Http\Requests\DestroyBulkSourcePhraseRequest;
use Outhebox\Translations\Http\Requests\StoreKeyRequest;
use Outhebox\Translations\Http\Requests\UpdateSourcePhraseRequest;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\Translation;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\Importer\ParameterExtractor;
use Outhebox\Translations\Services\KeyReplicator;
use Outhebox\Translations\Support\DataTable\BulkAction;
use Outhebox\Translations\Support\DataTable\Column;
use Outhebox\Translations\Support\DataTable\Filter;

class SourcePhraseController extends Controller
{
    use HasDataTable;

    private ?Language $sourceLanguage = null;

    protected function tableModel(): string
    {
        return TranslationKey::class;
    }

    protected function tableColumns(): array
    {
        return [
            Column::make('key', 'Key')->mono()->searchable()->sortable()->fixed()->maxSize(400),
            Column::make('group_name', 'Group')->maxSize(160),
            Column::make('source_value', 'Value')->fill()->truncate(80),
        ];
    }

    protected function tableFilters(): array
    {
        return [
            Filter::make('group_id')->label('Group')->icon('folder')
                ->fromModel(Group::query()->orderBy('name'), fn (Group $group) => $group->displayName()),
        ];
    }

    protected function tableFilterCallbacks(): array
    {
        return [
            'group_id' => fn (Builder $query, $value) => $query->where('group_id', (int) $value),
        ];
    }

    protected function tableRelations(): array
    {
        return ['group'];
    }

    protected function modifyQuery(Builder $query): Builder
    {
        $sourceLanguage = $this->sourceLanguage ?? Language::source();

        if ($sourceLanguage) {
            $query->with(['translations' => fn ($q) => $q->where('language_id', $sourceLanguage->id)]);
        }

        return $query;
    }

    protected function tableDefaultSort(): string
    {
        return 'key';
    }

    protected function resourceName(): array
    {
        return ['translation key', 'translation keys'];
    }

    protected function tableBulkActions(): array
    {
        return [
            BulkAction::make('delete')
                ->label('Delete')
                ->icon('trash')
                ->destructive()
                ->confirm(
                    'The selected translation keys and all their translations will be permanently deleted.',
                    'Delete Translation Keys',
                )
                ->action(function ($keys) {
                    foreach ($keys as $key) {
                        $key->translations()->delete();
                        $key->delete();
                    }

                    return redirect()->route('ltu.source.index')
                        ->with('success', count($keys).' translation keys deleted.');
                }),
        ];
    }

    protected function tableBulkActionRoute(): ?string
    {
        return 'ltu.source.bulk-action';
    }

    public function index(Request $request): Response
    {
        $this->sourceLanguage = Language::source();

        abort_unless($this->sourceLanguage, 404, 'No source language configured.');

        return Inertia::render('translations/source-language/index', $this->buildIndexData($request));
    }

    protected function buildIndexData(Request $request): array
    {
        $tableData = $this->paginatedTableData($request);

        $tableData['data'] = collect($tableData['data'])->map(function ($item) {
            $data = $item instanceof TranslationKey ? $item->toArray() : (array) $item;
            $data['key'] = ($data['group']['name'] ?? '').'.'.$data['key'];
            $data['source_value'] = $data['translations'][0]['value'] ?? null;
            $data['group_name'] = $data['group']['name'] ?? null;

            return $data;
        })->all();

        return [
            'data' => $tableData,
            'tableConfig' => $this->tableConfig($request),
            'groups' => Group::query()->orderBy('name')->get(),
            'sourceLanguage' => $this->sourceLanguage,
        ];
    }

    public function show(TranslationKey $translationKey): Response
    {
        $sourceLanguage = Language::source();

        abort_unless($sourceLanguage, 404, 'No source language configured.');

        return Inertia::render('translations/source-language/show', $this->buildShowData($translationKey, $sourceLanguage));
    }

    protected function buildShowData(TranslationKey $translationKey, Language $sourceLanguage): array
    {
        $translationKey->load(['group', 'translations' => fn ($q) => $q->where('language_id', $sourceLanguage->id)]);

        return [
            'sourceLanguage' => $sourceLanguage,
            'translationKey' => $translationKey,
            'groups' => Group::query()->orderBy('name')->get(),
            'previousKey' => TranslationKey::query()->where('group_id', $translationKey->group_id)->where('key', '<', $translationKey->key)->orderByDesc('key')->value('id'),
            'nextKey' => TranslationKey::query()->where('group_id', $translationKey->group_id)->where('key', '>', $translationKey->key)->orderBy('key')->value('id'),
        ];
    }

    public function store(StoreKeyRequest $request, ParameterExtractor $extractor, KeyReplicator $replicator): RedirectResponse
    {
        $validated = $request->validated();
        $value = $validated['value'] ?? '';

        $key = TranslationKey::query()->create([
            'group_id' => $validated['group_id'],
            'key' => $validated['key'],
            ...$this->extractMetadata($extractor, $value),
        ]);

        $replicator->replicateKey($key, $value ?: null);
        TranslationKeyCreated::dispatch($key);

        return redirect()->route('ltu.source.index')
            ->with('success', 'Translation key created.');
    }

    public function update(UpdateSourcePhraseRequest $request, TranslationKey $translationKey, ParameterExtractor $extractor): RedirectResponse|JsonResponse
    {
        $sourceLanguage = Language::source();
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $translationKey, $sourceLanguage, $extractor): void {
            $keyUpdates = Arr::only($validated, ['key', 'group_id', 'context_note', 'priority']);

            if ($keyUpdates) {
                $translationKey->update($keyUpdates);
            }

            if (array_key_exists('value', $validated) && $sourceLanguage) {
                $this->updateSourceTranslation($translationKey, $sourceLanguage, $validated['value'] ?? '', $extractor);
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Source phrase updated.']);
        }

        return redirect()->back()->with('success', 'Source phrase updated.');
    }

    private function extractMetadata(ParameterExtractor $extractor, string $value): array
    {
        if (! $value) {
            return ['parameters' => null, 'is_html' => false, 'is_plural' => false];
        }

        return [
            'parameters' => $extractor->extract($value) ?: null,
            'is_html' => $extractor->containsHtml($value),
            'is_plural' => $extractor->isPlural($value),
        ];
    }

    private function updateSourceTranslation(TranslationKey $translationKey, Language $sourceLanguage, string $value, ParameterExtractor $extractor): void
    {
        Translation::query()->updateOrCreate(
            ['translation_key_id' => $translationKey->id, 'language_id' => $sourceLanguage->id],
            [
                'value' => $value ?: null,
                'status' => $value ? TranslationStatus::Translated : TranslationStatus::Untranslated,
            ]
        );

        if ($value) {
            $translationKey->update($this->extractMetadata($extractor, $value));
        }
    }

    public function destroy(TranslationKey $translationKey): RedirectResponse
    {
        abort_unless(app('translations.auth')->role()?->canManageKeys(), 403);

        $translationKey->translations()->delete();
        $translationKey->delete();

        return redirect()->route('ltu.source.index')
            ->with('success', 'Translation key deleted.');
    }

    public function destroyBulk(DestroyBulkSourcePhraseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            Translation::query()->whereIn('translation_key_id', $validated['ids'])->delete();
            TranslationKey::query()->whereIn('id', $validated['ids'])->delete();
        });

        return redirect()->route('ltu.source.index')
            ->with('success', count($validated['ids']).' translation keys deleted.');
    }
}
