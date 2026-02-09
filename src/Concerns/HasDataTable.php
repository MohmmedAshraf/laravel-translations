<?php

declare(strict_types=1);

namespace Outhebox\Translations\Concerns;

use BackedEnum;
use Closure;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Outhebox\Translations\Contracts\HasLabel;
use Outhebox\Translations\Support\DataTable\BulkAction;
use Outhebox\Translations\Support\DataTable\Column;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Enums\SortDirection;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait HasDataTable
{
    protected array $bulkActionHandlers = [];

    abstract protected function tableModel(): string;

    abstract protected function tableColumns(): array;

    protected function tableBaseQuery(): string|Builder|Relation
    {
        return $this->tableModel();
    }

    protected function tableResource(): ?string
    {
        return null;
    }

    protected function tableFilters(): array
    {
        return [];
    }

    protected function tableFilterCallbacks(): array
    {
        return [];
    }

    protected function tableRelations(): array
    {
        return [];
    }

    protected function tableBulkActions(): array
    {
        return [];
    }

    protected function tableBulkActionRoute(): ?string
    {
        return null;
    }

    protected function tableBulkActionRouteParams(): array
    {
        return [];
    }

    protected function tableDefaultSort(): string
    {
        return '-created_at';
    }

    protected function tablePerPage(): int
    {
        $perPage = request()->integer('per_page', 15);
        $allowed = [10, 15, 25, 50, 100];

        return in_array($perPage, $allowed) ? $perPage : 15;
    }

    protected function resourceName(): ?array
    {
        return null;
    }

    protected function pivotFilterKeys(): array
    {
        return [];
    }

    protected function modifyQuery(Builder $query): Builder
    {
        return $query;
    }

    protected function resolveBulkActionRecords(array $ids): mixed
    {
        $model = $this->tableModel();

        return $model::whereIn((new $model)->getKeyName(), $ids)->get();
    }

    protected function tableQuery(Request $request): QueryBuilder
    {
        $searchableColumns = $this->getSearchableColumns();
        $allowedFilters = [];
        $pivotFilterKeys = $this->pivotFilterKeys();

        if (count($searchableColumns) === 1) {
            $allowedFilters[] = AllowedFilter::partial('search', $searchableColumns[0]);
        } elseif (count($searchableColumns) > 1) {
            $allowedFilters[] = AllowedFilter::callback('search', function (Builder $query, $value) use ($searchableColumns): void {
                $escaped = str_replace(['%', '_'], ['\%', '\_'], $value);
                $query->where(function (Builder $q) use ($escaped, $searchableColumns): void {
                    foreach ($searchableColumns as $column) {
                        $q->orWhere($column, 'like', "%{$escaped}%");
                    }
                });
            });
        }

        $filterCallbacks = $this->tableFilterCallbacks();

        foreach ($this->tableFilters() as $filter) {
            $key = is_array($filter) ? $filter['key'] : $filter->getKey();

            if (isset($filterCallbacks[$key])) {
                $allowedFilters[] = AllowedFilter::callback($key, $filterCallbacks[$key]);
            } elseif (in_array($key, $pivotFilterKeys)) {
                $allowedFilters[] = AllowedFilter::callback($key, function (Builder $query, $value) use ($key): void {
                    $query->wherePivot($key, $value);
                });
            } else {
                $allowedFilters[] = AllowedFilter::exact($key);
            }
        }

        $query = QueryBuilder::for($this->tableBaseQuery())
            ->allowedFilters($allowedFilters)
            ->allowedSorts($this->buildAllowedSorts())
            ->defaultSort($this->resolveDefaultSort());

        if ($relations = $this->tableRelations()) {
            $query->with($relations);
        }

        $this->modifyQuery($query->getEloquentBuilder());

        return $query;
    }

    protected function paginatedTableData(Request $request): array
    {
        $paginated = $this->tableQuery($request)
            ->paginate($this->tablePerPage())
            ->withQueryString();

        $resource = $this->tableResource();
        $items = $resource
            ? $resource::collection($paginated->items())->resolve()
            : $paginated->items();

        return [
            'data' => $items,
            'total' => $paginated->total(),
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'last_page' => $paginated->lastPage(),
            'from' => $paginated->firstItem(),
            'to' => $paginated->lastItem(),
        ];
    }

    protected function tableConfig(Request $request): array
    {
        $searchableColumns = $this->getSearchableColumns();

        return [
            'columns' => $this->resolveColumns(),
            'filters' => $this->prepareFilters(),
            'sortableColumns' => $this->getSortableColumns(),
            'searchColumn' => count($searchableColumns) === 1 ? $searchableColumns[0] : 'keyword',
            'resourceName' => $this->resolveResourceName(),
            'bulkActions' => $this->prepareBulkActions($this->tableBulkActionRoute()),
            'currentFilters' => [
                'search' => $request->query('filter.search'),
                'filter' => $request->query('filter'),
                'sort' => $request->query('sort'),
            ],
        ];
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->tableQuery($request)->getEloquentBuilder();
        $columns = $this->exportColumns();
        [$singular, $plural] = $this->resolveResourceName();

        return response()->streamDownload(
            function () use ($query, $columns): void {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, array_column($columns, 'header'));

                foreach ($query->cursor() as $row) {
                    fputcsv($handle, $this->extractExportValues($row, $columns));
                }

                fclose($handle);
            },
            "{$plural}.csv",
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }

    protected function extractExportValues(mixed $row, array $columns): array
    {
        return array_map(function (array $column) use ($row): string {
            $value = data_get($row, $column['key'] ?? $column['header']);

            if ($value instanceof DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            if (is_array($value)) {
                return implode(', ', $value);
            }

            if (is_bool($value)) {
                return $value ? 'Yes' : 'No';
            }

            if ($value instanceof BackedEnum) {
                return $value instanceof HasLabel ? $value->getLabel() : $value->value;
            }

            return (string) ($value ?? '');
        }, $columns);
    }

    protected function prepareFilters(): array
    {
        return collect($this->tableFilters())
            ->map(fn ($filter) => $filter->toArray())
            ->all();
    }

    protected function prepareBulkActions(?string $bulkActionRoute): array
    {
        $this->bulkActionHandlers = [];
        $routeParams = $this->tableBulkActionRouteParams();

        return collect($this->tableBulkActions())
            ->map(function (BulkAction $action) use ($bulkActionRoute, $routeParams) {
                if ($action->hasHandler() && $bulkActionRoute) {
                    $this->bulkActionHandlers[$action->getName()] = $action->getHandler();
                    $action->setUrl(route($bulkActionRoute, array_merge($routeParams, ['action' => $action->getName()])));
                }

                return $action->toArray();
            })
            ->all();
    }

    public function bulkAction(Request $request, string $action): mixed
    {
        $bulkActions = $this->tableBulkActions();

        $bulkAction = collect($bulkActions)->first(
            fn (BulkAction $ba) => $ba->getName() === $action && $ba->hasHandler()
        );

        if (! $bulkAction) {
            abort(404, "Bulk action [{$action}] not found.");
        }

        $ids = $request->input('ids', []);
        $records = $this->resolveBulkActionRecords($ids);

        return ($bulkAction->getHandler())($records, $request);
    }

    protected function exportColumns(): array
    {
        return collect($this->resolveColumns())->map(fn (array $column) => [
            'header' => $column['header'],
            'key' => $column['accessorKey'] ?? $column['id'],
        ])->all();
    }

    private function resolveColumns(): array
    {
        return collect($this->tableColumns())
            ->map(fn ($column) => is_array($column) ? $column : $column->toArray())
            ->all();
    }

    private function getSortableColumns(): array
    {
        return collect($this->resolveColumns())
            ->filter(fn (array $column) => $column['sortable'] ?? false)
            ->pluck('id')
            ->all();
    }

    protected function pivotTable(): ?string
    {
        return null;
    }

    private function resolveDefaultSort(): AllowedSort|string
    {
        $default = $this->tableDefaultSort();
        $descending = str_starts_with($default, '-');
        $column = ltrim($default, '-');

        if (str_starts_with($column, 'pivot.')) {
            $pivotColumn = Str::after($column, 'pivot.');
            $table = $this->pivotTable();
            $sort = AllowedSort::callback($column, function ($query, bool $desc) use ($pivotColumn, $table): void {
                $qualified = $table ? "{$table}.{$pivotColumn}" : $pivotColumn;
                $query->orderBy($qualified, $desc ? 'desc' : 'asc');
            });

            if ($descending) {
                $sort->defaultDirection(SortDirection::DESCENDING);
            }

            return $sort;
        }

        return $default;
    }

    private function buildAllowedSorts(): array
    {
        $table = $this->pivotTable();

        return collect($this->tableColumns())
            ->filter(fn ($column) => $column instanceof Column ? ($column->toArray()['sortable'] ?? false) : ($column['sortable'] ?? false))
            ->map(function ($column) use ($table) {
                $isObject = $column instanceof Column;
                $id = $isObject ? $column->getId() : $column['id'];
                $sortColumn = $isObject ? $column->getSortColumn() : ($column['sortColumn'] ?? null);

                if (str_starts_with($id, 'pivot.')) {
                    $pivotColumn = Str::after($id, 'pivot.');

                    return AllowedSort::callback($id, function ($query, bool $descending) use ($pivotColumn, $table): void {
                        $qualified = $table ? "{$table}.{$pivotColumn}" : $pivotColumn;
                        $query->orderBy($qualified, $descending ? 'desc' : 'asc');
                    });
                }

                if ($sortColumn instanceof Closure) {
                    return AllowedSort::callback($id, $sortColumn);
                }

                if (is_string($sortColumn)) {
                    return AllowedSort::callback($id, function ($query, bool $descending) use ($sortColumn): void {
                        $query->orderBy($sortColumn, $descending ? 'desc' : 'asc');
                    });
                }

                return AllowedSort::field($id);
            })
            ->all();
    }

    private function getSearchableColumns(): array
    {
        return collect($this->resolveColumns())
            ->filter(fn (array $column) => $column['searchable'] ?? false)
            ->pluck('id')
            ->all();
    }

    private function resolveResourceName(): array
    {
        if ($name = $this->resourceName()) {
            return $name;
        }

        $model = $this->tableModel();
        $basename = class_basename($model);
        $singular = Str::snake($basename, ' ');
        $plural = Str::plural($singular);

        return [$singular, $plural];
    }
}
