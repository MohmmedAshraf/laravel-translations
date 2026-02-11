<?php

use Illuminate\Http\Request;
use Outhebox\Translations\Concerns\HasDataTable;
use Outhebox\Translations\Models\Group;
use Outhebox\Translations\Models\TranslationKey;
use Outhebox\Translations\Support\DataTable\Column;
use Outhebox\Translations\Support\DataTable\Filter;
use Symfony\Component\HttpFoundation\StreamedResponse;

function createMinimalController(): object
{
    return new class
    {
        use HasDataTable;

        protected function tableModel(): string
        {
            return TranslationKey::class;
        }

        protected function tableColumns(): array
        {
            return [
                Column::make('key', 'Key')->searchable()->sortable(),
            ];
        }

        public function callExport(Request $request): StreamedResponse
        {
            return $this->export($request);
        }

        public function callExportColumns(): array
        {
            return $this->exportColumns();
        }

        public function callPaginatedTableData(Request $request): array
        {
            return $this->paginatedTableData($request);
        }

        public function callTableConfig(Request $request): array
        {
            return $this->tableConfig($request);
        }

        public function callTableQuery(Request $request): mixed
        {
            return $this->tableQuery($request);
        }

        public function callResolveDefaultSort(): mixed
        {
            $method = new ReflectionMethod($this, 'resolveDefaultSort');

            return $method->invoke($this);
        }

        public function callBuildAllowedSorts(): array
        {
            $method = new ReflectionMethod($this, 'buildAllowedSorts');

            return $method->invoke($this);
        }

        public function callPrepareBulkActions(?string $route): array
        {
            return $this->prepareBulkActions($route);
        }

        public function callPrepareFilters(): array
        {
            return $this->prepareFilters();
        }

        public function callBulkAction(Request $request, string $action): mixed
        {
            return $this->bulkAction($request, $action);
        }
    };
}

function createPivotController(): object
{
    return new class
    {
        use HasDataTable;

        protected function tableModel(): string
        {
            return TranslationKey::class;
        }

        protected function tableColumns(): array
        {
            return [
                Column::make('pivot.priority', 'Priority')->sortable(),
                Column::make('key', 'Key')->sortable()->sortColumn('ltu_translation_keys.key'),
                Column::make('custom', 'Custom')->sortable()->sortColumn(function ($query, bool $descending): void {
                    $query->orderBy('created_at', $descending ? 'desc' : 'asc');
                }),
            ];
        }

        protected function tableDefaultSort(): string
        {
            return '-pivot.priority';
        }

        protected function pivotTable(): ?string
        {
            return 'ltu_translations';
        }

        protected function pivotFilterKeys(): array
        {
            return ['language_id'];
        }

        protected function tableFilters(): array
        {
            return [
                Filter::make('language_id')->label('Language'),
            ];
        }

        public function callResolveDefaultSort(): mixed
        {
            $method = new ReflectionMethod($this, 'resolveDefaultSort');

            return $method->invoke($this);
        }

        public function callBuildAllowedSorts(): array
        {
            $method = new ReflectionMethod($this, 'buildAllowedSorts');

            return $method->invoke($this);
        }

        public function callTableQuery(Request $request): mixed
        {
            return $this->tableQuery($request);
        }
    };
}

it('returns base default values for unoverridden methods', function () {
    $controller = createMinimalController();

    $refFilters = new ReflectionMethod($controller, 'tableFilters');
    $refCallbacks = new ReflectionMethod($controller, 'tableFilterCallbacks');
    $refRelations = new ReflectionMethod($controller, 'tableRelations');
    $refResource = new ReflectionMethod($controller, 'tableResource');
    $refBulkActions = new ReflectionMethod($controller, 'tableBulkActions');
    $refBulkRoute = new ReflectionMethod($controller, 'tableBulkActionRoute');
    $refBulkRouteParams = new ReflectionMethod($controller, 'tableBulkActionRouteParams');
    $refResourceName = new ReflectionMethod($controller, 'resourceName');
    $refPivotFilterKeys = new ReflectionMethod($controller, 'pivotFilterKeys');
    $refPivotTable = new ReflectionMethod($controller, 'pivotTable');

    expect($refFilters->invoke($controller))->toBe([]);
    expect($refCallbacks->invoke($controller))->toBe([]);
    expect($refRelations->invoke($controller))->toBe([]);
    expect($refResource->invoke($controller))->toBeNull();
    expect($refBulkActions->invoke($controller))->toBe([]);
    expect($refBulkRoute->invoke($controller))->toBeNull();
    expect($refBulkRouteParams->invoke($controller))->toBe([]);
    expect($refResourceName->invoke($controller))->toBeNull();
    expect($refPivotFilterKeys->invoke($controller))->toBe([]);
    expect($refPivotTable->invoke($controller))->toBeNull();
});

it('returns default sort as -created_at', function () {
    $controller = createMinimalController();
    $result = $controller->callResolveDefaultSort();

    expect($result)->toBe('-created_at');
});

it('modifyQuery returns query unchanged by default', function () {
    $controller = createMinimalController();
    $query = TranslationKey::query();
    $ref = new ReflectionMethod($controller, 'modifyQuery');

    expect($ref->invoke($controller, $query))->toBe($query);
});

it('exports data as CSV stream', function () {
    Group::factory()->create(['name' => 'test']);
    TranslationKey::factory()->count(3)->create();

    $controller = createMinimalController();
    $request = Request::create('/test', 'GET');
    $response = $controller->callExport($request);

    expect($response)->toBeInstanceOf(StreamedResponse::class);
    expect($response->headers->get('Content-Type'))->toBe('text/csv; charset=UTF-8');

    ob_start();
    $response->sendContent();
    $csv = ob_get_clean();

    $lines = array_filter(explode("\n", trim($csv)));
    expect(count($lines))->toBe(4); // header + 3 rows
    expect($lines[0])->toContain('Key');
});

it('generates export columns from table columns', function () {
    $controller = createMinimalController();
    $columns = $controller->callExportColumns();

    expect($columns)->toBeArray();
    expect($columns[0]['header'])->toBe('Key');
    expect($columns[0]['key'])->toBe('key');
});

it('paginates table data correctly', function () {
    TranslationKey::factory()->count(5)->create();

    $controller = createMinimalController();
    $request = Request::create('/test', 'GET');
    $data = $controller->callPaginatedTableData($request);

    expect($data)->toHaveKeys(['data', 'total', 'current_page', 'per_page', 'last_page', 'from', 'to']);
    expect($data['total'])->toBe(5);
});

it('returns table config with all keys', function () {
    $controller = createMinimalController();
    $request = Request::create('/test', 'GET');
    $config = $controller->callTableConfig($request);

    expect($config)->toHaveKeys(['columns', 'filters', 'sortableColumns', 'searchColumn', 'resourceName', 'bulkActions', 'currentFilters']);
    expect($config['searchColumn'])->toBe('key');
});

it('resolves pivot default sort', function () {
    $controller = createPivotController();
    $result = $controller->callResolveDefaultSort();

    expect($result)->toBeInstanceOf(Spatie\QueryBuilder\AllowedSort::class);
});

it('builds allowed sorts with pivot columns', function () {
    $controller = createPivotController();
    $sorts = $controller->callBuildAllowedSorts();

    expect($sorts)->toHaveCount(3);
});

it('builds query with pivot filter keys', function () {
    $controller = createPivotController();
    $request = Request::create('/test', 'GET');
    $query = $controller->callTableQuery($request);

    expect($query)->not->toBeNull();
});

it('prepares empty filters', function () {
    $controller = createMinimalController();
    $filters = $controller->callPrepareFilters();

    expect($filters)->toBe([]);
});

it('prepares empty bulk actions', function () {
    $controller = createMinimalController();
    $actions = $controller->callPrepareBulkActions(null);

    expect($actions)->toBe([]);
});

it('tableBaseQuery returns model class string by default', function () {
    $controller = createMinimalController();
    $ref = new ReflectionMethod($controller, 'tableBaseQuery');

    expect($ref->invoke($controller))->toBe(TranslationKey::class);
});

it('tablePerPage defaults to 15 for invalid value', function () {
    $controller = createMinimalController();

    // Test with no per_page parameter
    $request = Request::create('/test');
    app()->instance('request', $request);
    $ref = new ReflectionMethod($controller, 'tablePerPage');

    expect($ref->invoke($controller))->toBe(15);
});
