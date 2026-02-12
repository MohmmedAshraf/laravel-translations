import { useCallback, useMemo, useState } from 'react';
import type { Column } from '@/components/data-table/data-table';
import { DataTable } from '@/components/data-table/data-table';
import type { ColumnConfig } from '@/components/data-table/data-table-column-toggle';
import { DataTableColumnToggle } from '@/components/data-table/data-table-column-toggle';
import { DataTableExport } from '@/components/data-table/data-table-export';
import { DataTableRowActions } from '@/components/data-table/data-table-row-actions';
import { DataTableToolbar } from '@/components/data-table/data-table-toolbar';
import { useDataTableFilters } from '@/components/data-table/hooks/use-data-table-filters';
import type { Filter } from '@/components/ui/filter-select';
import { FilterList, FilterSelect } from '@/components/ui/filter-select';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type {
    AdminDataTableProps,
    TableColumnConfig,
} from './admin-data-table.types';
import {
    renderCellValue,
    formatDate,
    getNestedValue,
} from './column-renderers';
import { DataTableBulkActions } from './data-table-bulk-actions';
import { renderIcon } from './icon-map';

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function AdminDataTable<T extends Record<string, any>>({
    data,
    tableConfig,
    route,
    placeholderData,
    emptyState,
    onRowClick,
    rowActions,
    getRowId,
    toolbarActions,
    columnStorageKey,
    exportFilename,
    serverExport = false,
    customCellRenderers,
    compactToolbar = false,
    isRowSelectable,
    onBulkAction,
}: AdminDataTableProps<T>) {
    const {
        search,
        isLoading,
        activeFilters,
        sortBy,
        sortOrder,
        isFiltered,
        handleSearchChange,
        handleSearchDebounced,
        handleFilterSelect,
        handleFilterRemove,
        handleFilterRemoveAll,
        handleSortChange,
        handlePageChange,
        handlePerPageChange,
    } = useDataTableFilters({
        route,
        initialFilters: tableConfig.currentFilters,
        only: ['data', 'tableConfig'],
    });

    const initialColumnConfigs = useMemo(
        (): ColumnConfig[] =>
            tableConfig.columns.map((col) => ({
                id: col.id,
                label: col.header,
                visible: col.visible !== false,
                canHide: col.canHide !== false,
            })),
        [tableConfig.columns],
    );

    const [columnConfigs, setColumnConfigs] =
        useState<ColumnConfig[]>(initialColumnConfigs);

    const [selectedIds, setSelectedIds] = useState<(string | number)[]>([]);
    const [selectAllPages, setSelectAllPages] = useState(false);
    const [prevData, setPrevData] = useState(data);
    const [prevFilters, setPrevFilters] = useState(tableConfig.currentFilters);

    const filtersChanged = prevFilters !== tableConfig.currentFilters;
    const dataChanged = prevData !== data;

    if (filtersChanged || dataChanged) {
        if (filtersChanged) {
            setPrevFilters(tableConfig.currentFilters);
            setSelectAllPages(false);
        }
        if (dataChanged) {
            setPrevData(data);
        }
        if (filtersChanged || !selectAllPages) {
            setSelectedIds([]);
        }
    }

    const bulkActions = tableConfig.bulkActions ?? [];
    const isSelectable = bulkActions.length > 0;

    const visibleColumnIds = useMemo(
        () => columnConfigs.filter((c) => c.visible).map((c) => c.id),
        [columnConfigs],
    );

    const filterConfig = useMemo(
        (): Filter[] =>
            tableConfig.filters.map((f) => ({
                key: f.key,
                label: f.label,
                icon: renderIcon(f.icon, undefined, 'size-4'),
                searchable: f.searchable,
                options: f.options.map((opt) => ({
                    value: opt.value,
                    label: opt.label,
                    icon: renderIcon(opt.icon, opt.iconColor, 'size-4'),
                })),
            })),
        [tableConfig.filters],
    );

    const allColumns = useMemo(
        (): Column<T>[] =>
            tableConfig.columns.map((colConfig: TableColumnConfig) => ({
                id: colConfig.id,
                header: colConfig.headerIcon ? (
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <span>
                                {renderIcon(
                                    colConfig.headerIcon,
                                    undefined,
                                    'size-4',
                                )}
                            </span>
                        </TooltipTrigger>
                        <TooltipContent>{colConfig.header}</TooltipContent>
                    </Tooltip>
                ) : (
                    colConfig.header
                ),
                minSize: colConfig.minSize,
                maxSize: colConfig.maxSize,
                fill: colConfig.fill,
                cellClassName: colConfig.cellClassName,
                headerClassName: colConfig.headerClassName,
                cell: customCellRenderers?.[colConfig.id]
                    ? (row: T, index: number) =>
                          customCellRenderers[colConfig.id](row, index)
                    : (row: T) => renderCellValue(row, colConfig),
            })),
        [tableConfig.columns, customCellRenderers],
    );

    const columns = useMemo(
        () => allColumns.filter((col) => visibleColumnIds.includes(col.id)),
        [allColumns, visibleColumnIds],
    );

    const exportColumns = useMemo(
        () =>
            tableConfig.columns.map((col) => ({
                id: col.id,
                header: col.header,
                accessorFn: (row: Record<string, unknown>) => {
                    const item = row as T;
                    const key = col.accessorKey ?? col.id;
                    const value = key.includes('.')
                        ? getNestedValue(item, key)
                        : item[key];

                    if (value === null || value === undefined) {
                        return col.emptyValue ?? '';
                    }

                    switch (col.type) {
                        case 'date':
                            return formatDate(String(value), col.dateFormat);
                        case 'datetime':
                            return formatDate(String(value), {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                            });
                        case 'list':
                            return Array.isArray(value)
                                ? value.join(', ')
                                : String(value);
                        case 'badge':
                            if (col.badgeMap && col.badgeMap[String(value)]) {
                                return (
                                    col.badgeMap[String(value)].label ??
                                    String(value)
                                );
                            }
                            return String(value);
                        case 'boolean':
                            return value ? 'Yes' : 'No';
                        default:
                            return String(value);
                    }
                },
            })),
        [tableConfig.columns],
    );

    const renderRowActions = useCallback(
        (row: T) => {
            if (!rowActions || rowActions.length === 0) return null;

            const actions = rowActions
                .filter((action) => {
                    if (typeof action.hidden === 'function') {
                        return !action.hidden(row);
                    }
                    return !action.hidden;
                })
                .map((action) => ({
                    label: action.label,
                    icon: action.icon,
                    onClick: (e?: React.MouseEvent) => action.onClick(row, e),
                    variant: action.variant,
                    disabled:
                        typeof action.disabled === 'function'
                            ? action.disabled(row)
                            : action.disabled,
                }));

            return <DataTableRowActions actions={actions} />;
        },
        [rowActions],
    );

    const handleSelectionChange = useCallback(
        (ids: (string | number)[]) => {
            if (selectAllPages) {
                setSelectAllPages(false);
            }
            setSelectedIds(ids);
        },
        [selectAllPages],
    );

    const handleClearSelection = useCallback(() => {
        setSelectAllPages(false);
        setSelectedIds([]);
    }, []);

    const hasData = data.total > 0;
    const [singular, plural] = tableConfig.resourceName;

    return (
        <div className="flex min-h-0 flex-1 flex-col gap-4">
            <DataTableToolbar
                search={search}
                searchLoading={isLoading}
                onSearchChange={handleSearchChange}
                onSearchChangeDebounced={handleSearchDebounced}
                searchPlaceholder={`Search by ${tableConfig.searchColumn}...`}
                searchDisabled={!hasData && !isFiltered}
                filters={
                    filterConfig.length > 0 ? (
                        <FilterSelect
                            filters={filterConfig}
                            activeFilters={activeFilters}
                            onSelect={handleFilterSelect}
                            onRemove={handleFilterRemove}
                            disabled={!hasData && !isFiltered}
                        />
                    ) : undefined
                }
                actions={
                    <>
                        {toolbarActions}
                        <DataTableColumnToggle
                            columns={columnConfigs}
                            onChange={setColumnConfigs}
                            storageKey={columnStorageKey}
                            disabled={!hasData && !isFiltered}
                            iconOnly={compactToolbar}
                            defaultColumns={initialColumnConfigs}
                        />
                        {exportFilename && (
                            <DataTableExport
                                data={
                                    data.data as unknown as Record<
                                        string,
                                        unknown
                                    >[]
                                }
                                columns={exportColumns}
                                filename={exportFilename}
                                serverExport={serverExport}
                                iconOnly={compactToolbar}
                            />
                        )}
                    </>
                }
            />

            {filterConfig.length > 0 && (
                <FilterList
                    filters={filterConfig}
                    activeFilters={activeFilters}
                    onRemove={handleFilterRemove}
                    onRemoveAll={handleFilterRemoveAll}
                />
            )}

            <DataTable
                data={data.data}
                columns={columns}
                loading={isLoading}
                placeholderData={placeholderData}
                emptyState={emptyState}
                isFiltered={isFiltered}
                onClearFilters={handleFilterRemoveAll}
                sortBy={sortBy}
                sortOrder={sortOrder}
                onSortChange={handleSortChange}
                sortableColumns={tableConfig.sortableColumns}
                pagination={{
                    pageIndex: data.current_page,
                    pageSize: data.per_page,
                }}
                rowCount={data.total}
                onPageChange={handlePageChange}
                onPerPageChange={handlePerPageChange}
                resourceName={(p) => (p ? plural : singular)}
                onRowClick={onRowClick}
                getRowId={getRowId}
                rowActions={rowActions ? renderRowActions : undefined}
                selectable={isSelectable}
                selectedIds={selectedIds}
                onSelectionChange={handleSelectionChange}
                isRowSelectable={isRowSelectable}
                selectAllPages={selectAllPages}
                onSelectAllPages={setSelectAllPages}
            />

            {isSelectable && (
                <DataTableBulkActions
                    selectedIds={selectedIds}
                    actions={bulkActions}
                    onClearSelection={handleClearSelection}
                    resourceName={selectedIds.length === 1 ? singular : plural}
                    onAction={onBulkAction}
                    selectAllPages={selectAllPages}
                    totalCount={data.total}
                />
            )}
        </div>
    );
}
