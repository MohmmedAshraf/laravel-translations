<?php

namespace Outhebox\Translations\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Outhebox\Translations\Concerns\HasDataTable;
use Outhebox\Translations\Enums\ContributorRole;
use Outhebox\Translations\Enums\LanguageStatus;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Events\LanguageAdded;
use Outhebox\Translations\Http\Requests\StoreCustomLanguageRequest;
use Outhebox\Translations\Http\Requests\StoreLanguageRequest;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Services\KeyReplicator;
use Outhebox\Translations\Support\DataTable\BulkAction;
use Outhebox\Translations\Support\DataTable\Column;
use Outhebox\Translations\Support\DataTable\Filter;

class LanguageController extends Controller
{
    use HasDataTable;

    protected function tableModel(): string
    {
        return Language::class;
    }

    protected function tableBaseQuery(): string|Builder
    {
        $query = Language::query()->active();

        $auth = app('translations.auth');
        $role = $auth->role();

        if ($role && ! $role->isAtLeast(ContributorRole::Admin)) {
            $assignedIds = $auth->assignedLanguageIds();

            if ($assignedIds->isNotEmpty()) {
                $query->whereIn('id', $assignedIds);
            }
        }

        return $query;
    }

    protected function tableColumns(): array
    {
        return [
            Column::make('status', 'State')->statusIcon(LanguageStatus::class)->headerIcon('activity')->maxSize(50),
            Column::make('name', 'Language')->language()->searchable()->fixed()->maxSize(400),
            Column::make('progress', 'Progress')->progress()->sortable()->sortColumn(function ($query, bool $descending): void {
                $query->orderByDesc('is_source')->orderBy('translated_count', $descending ? 'desc' : 'asc');
            })->fixed(),
            Column::make('last_activity', 'Last Activity')->relativeTime()->sortable()->sortColumn('translations_max_updated_at')->maxSize(160)->hidden(),
        ];
    }

    protected function tableFilters(): array
    {
        return [
            Filter::make('status', LanguageStatus::class)->label('Status')->icon('check-circle'),
        ];
    }

    protected function tableFilterCallbacks(): array
    {
        return [
            'status' => function (Builder $query, $value): void {
                match ($value) {
                    'completed' => $query
                        ->whereHas('translations')
                        ->whereDoesntHave('translations', function ($q): void {
                            $q->whereIn('status', [TranslationStatus::Untranslated->value, TranslationStatus::NeedsReview->value]);
                        }),
                    'in_progress' => $query
                        ->whereHas('translations', function ($q): void {
                            $q->whereIn('status', [TranslationStatus::Translated->value, TranslationStatus::Approved->value]);
                        })
                        ->whereHas('translations', function ($q): void {
                            $q->where('status', TranslationStatus::Untranslated->value);
                        }),
                    'needs_review' => $query
                        ->whereHas('translations', function ($q): void {
                            $q->where('status', TranslationStatus::NeedsReview->value);
                        }),
                    'not_started' => $query
                        ->whereDoesntHave('translations', function ($q): void {
                            $q->whereIn('status', [TranslationStatus::Translated->value, TranslationStatus::Approved->value]);
                        }),
                    default => null,
                };
            },
        ];
    }

    protected function tableDefaultSort(): string
    {
        return '-is_source';
    }

    protected function resourceName(): array
    {
        return ['language', 'languages'];
    }

    protected function tableBulkActions(): array
    {
        return [
            BulkAction::make('delete')
                ->label('Delete')
                ->icon('trash')
                ->destructive()
                ->confirm(
                    'The selected languages and all their translations will be permanently deleted.',
                    'Delete Languages',
                )
                ->action(function ($languages) {
                    $skipped = 0;

                    foreach ($languages as $language) {
                        if ($language->isSource()) {
                            $skipped++;

                            continue;
                        }

                        $language->translations()->delete();
                        $language->delete();
                    }

                    if ($skipped > 0) {
                        return redirect()->route('ltu.languages.index')
                            ->with('warning', 'Source language cannot be deleted and was skipped.');
                    }

                    return redirect()->route('ltu.languages.index')
                        ->with('success', 'Languages deleted.');
                }),
        ];
    }

    protected function tableBulkActionRoute(): ?string
    {
        return 'ltu.languages.bulk-action';
    }

    protected function modifyQuery(Builder $query): Builder
    {
        return $query->withCount([
            'translations as translated_count' => function ($q): void {
                $q->whereIn('status', [TranslationStatus::Translated->value, TranslationStatus::Approved->value]);
            },
            'translations as untranslated_count' => function ($q): void {
                $q->where('status', TranslationStatus::Untranslated->value);
            },
            'translations as needs_review_count' => function ($q): void {
                $q->where('status', TranslationStatus::NeedsReview->value);
            },
        ])->withMax('translations', 'updated_at');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('translations/languages', $this->buildIndexData($request));
    }

    protected function buildIndexData(Request $request): array
    {
        $totalKeys = TranslationKey::query()->count();
        $tableData = $this->paginatedTableData($request);

        $tableData['data'] = collect($tableData['data'])
            ->map(fn ($language) => $this->enrichLanguageData($language, $totalKeys))
            ->all();

        return [
            'data' => $tableData,
            'tableConfig' => $this->tableConfig($request),
            'availableLanguages' => Language::query()->where('active', false)->orderBy('name')->get(['id', 'code', 'name', 'native_name', 'rtl']),
            'totalKeys' => $totalKeys,
        ];
    }

    private function enrichLanguageData(mixed $language, int $totalKeys): array
    {
        $data = $language instanceof Language ? $language->toArray() : (array) $language;
        $translatedCount = $data['translated_count'] ?? 0;
        $rawDate = $data['translations_max_updated_at'] ?? null;

        $data['progress'] = $totalKeys > 0 ? round(($translatedCount / $totalKeys) * 100, 1) : 0;
        $data['total_keys'] = $totalKeys;
        $data['last_activity'] = $rawDate ? Carbon::parse($rawDate)->diffForHumans() : null;
        $data['last_activity_at'] = $rawDate;
        $data['status'] = $this->resolveLanguageStatus($data, $totalKeys)->value;

        return $data;
    }

    private function resolveLanguageStatus(array $data, int $totalKeys): LanguageStatus
    {
        $translatedCount = $data['translated_count'] ?? 0;
        $needsReviewCount = $data['needs_review_count'] ?? 0;
        $untranslatedCount = $data['untranslated_count'] ?? 0;

        if ($totalKeys === 0 || $untranslatedCount === $totalKeys) {
            return LanguageStatus::NotStarted;
        }

        if ($needsReviewCount > 0) {
            return LanguageStatus::NeedsReview;
        }

        if ($translatedCount >= $totalKeys) {
            return LanguageStatus::Completed;
        }

        return LanguageStatus::InProgress;
    }

    public function store(StoreLanguageRequest $request, KeyReplicator $replicator): RedirectResponse
    {
        $validated = $request->validated();

        $added = [];

        $languages = Language::query()->whereIn('id', $validated['language_ids'])->get();

        DB::transaction(function () use ($languages, $replicator, &$added): void {
            foreach ($languages as $language) {
                if ($language->active) {
                    continue;
                }

                $language->update(['active' => true]);
                $replicator->replicateForLanguage($language);
                LanguageAdded::dispatch($language);
                $added[] = $language->name;
            }
        });

        if (empty($added)) {
            return redirect()->route('ltu.languages.index')
                ->with('info', 'All selected languages are already active.');
        }

        $message = count($added) === 1
            ? "Language '{$added[0]}' added."
            : count($added).' languages added.';

        return redirect()->route('ltu.languages.index')
            ->with('success', $message);
    }

    public function storeCustom(StoreCustomLanguageRequest $request, KeyReplicator $replicator): RedirectResponse
    {
        $language = DB::transaction(function () use ($request, $replicator) {
            $language = Language::query()->create([
                ...$request->validated(),
                'active' => true,
                'is_source' => false,
            ]);

            $replicator->replicateForLanguage($language);
            LanguageAdded::dispatch($language);

            return $language;
        });

        return redirect()->route('ltu.languages.index')
            ->with('success', "Language '{$language->name}' created.");
    }

    public function destroy(Request $request, Language $language): RedirectResponse
    {
        if ($language->isSource()) {
            return redirect()->route('ltu.languages.index')
                ->with('error', 'Cannot remove the source language.');
        }

        if ($request->boolean('delete_translations')) {
            $language->translations()->delete();
        }

        $language->update(['active' => false]);

        return redirect()->route('ltu.languages.index')
            ->with('success', "Language '{$language->name}' removed.");
    }
}
