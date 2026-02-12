import { useCallback, useMemo } from 'react';
import {
    DataTableEmptyState,
    type EmptyStateConfig,
    NoResultsState,
} from '@/components/data-table/data-table-empty-state';
import { DataTableFooter } from '@/components/data-table/data-table-footer';
import { DataTableHead } from '@/components/data-table/data-table-head';
import { DataTableRow } from '@/components/data-table/data-table-row';
import type {
    Column,
    DataTableProps,
    Pagination,
} from '@/components/data-table/data-table.types';
import { useDataTableSelection } from '@/components/data-table/hooks/use-data-table-selection';
import { useDataTableSorting } from '@/components/data-table/hooks/use-data-table-sorting';
import { Spinner } from '@/components/ui/spinner';
import { cn } from '@/lib/utils';

export type { EmptyStateConfig };
export type { Column, Pagination };

export function DataTable<T>({
    data,
    columns,
    loading,
    error,
    placeholderData,
    emptyState,
    isFiltered = false,
    onClearFilters,
    sortBy,
    sortOrder,
    onSortChange,
    sortableColumns = [],
    pagination,
    rowCount,
    onPageChange,
    onPerPageChange,
    resourceName = (p) => (p ? 'items' : 'item'),
    onRowClick,
    rowActions,
    getRowId,
    className,
    containerClassName,
    thClassName,
    tdClassName,
    selectable = false,
    selectedIds = [],
    onSelectionChange,
    isRowSelectable,
    selectAllPages = false,
    onSelectAllPages,
}: DataTableProps<T>) {
    const getRowIdValue = useCallback(
        (row: T, index: number): string | number => {
            return getRowId ? getRowId(row) : index;
        },
        [getRowId],
    );

    const effectiveSelectedIds = useMemo(() => {
        if (selectAllPages) {
            return data.map((row, index) => getRowIdValue(row, index));
        }
        return selectedIds;
    }, [selectAllPages, data, selectedIds, getRowIdValue]);

    const handleSelectionChangeWrapper = useCallback(
        (ids: (string | number)[]) => {
            if (selectAllPages) {
                onSelectAllPages?.(false);
            }
            onSelectionChange?.(ids);
        },
        [selectAllPages, onSelectAllPages, onSelectionChange],
    );

    const {
        allSelected,
        someSelected,
        handleSelectAll,
        handleToggleRowSelection,
    } = useDataTableSelection({
        data,
        selectable,
        selectedIds: effectiveSelectedIds,
        onSelectionChange: handleSelectionChangeWrapper,
        getRowIdValue,
        isRowSelectable,
    });

    const handleSort = useDataTableSorting({
        sortBy,
        sortOrder,
        onSortChange,
        sortableColumns,
    });

    const totalPages =
        pagination && rowCount ? Math.ceil(rowCount / pagination.pageSize) : 0;

    const totalColSpan =
        columns.length + (selectable ? 1 : 0) + (rowActions ? 1 : 0);

    const showSelectAllBanner =
        selectable &&
        allSelected &&
        !selectAllPages &&
        rowCount !== undefined &&
        rowCount > data.length;

    const hasNoData = data.length === 0;
    const showPlaceholder = hasNoData && !isFiltered && !!placeholderData;
    const showNoResults = hasNoData && isFiltered;
    const tableData = showPlaceholder ? placeholderData : data;
    const isClickable = !!onRowClick && !showPlaceholder;

    if (error) {
        return (
            <div
                className={cn(
                    'relative z-0 flex min-h-0 flex-1 flex-col overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900',
                    containerClassName,
                )}
            >
                <div className="flex flex-1 items-center justify-center py-12 text-sm text-neutral-500 dark:text-neutral-400">
                    {error}
                </div>
            </div>
        );
    }

    if (!loading && showNoResults) {
        return (
            <div
                className={cn(
                    'relative z-0 flex min-h-0 flex-1 flex-col overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900',
                    containerClassName,
                )}
            >
                <NoResultsState onClearFilters={onClearFilters} />
            </div>
        );
    }

    return (
        <div
            className={cn(
                'relative z-0 flex min-h-0 flex-1 flex-col overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900',
                containerClassName,
            )}
        >
            <div className="relative min-h-0 flex-1 overflow-auto">
                <table
                    className={cn(
                        'group/table w-full table-fixed border-separate border-spacing-0 transition-[border-spacing,margin-top]',
                        '[&_tr>*:first-child]:border-l-transparent',
                        '[&_tr>*:last-child]:border-r-transparent',
                        '[&_th]:relative [&_th]:select-none',
                        className,
                    )}
                >
                    <DataTableHead
                        columns={columns}
                        selectable={selectable}
                        rowActions={!!rowActions}
                        allSelected={allSelected}
                        someSelected={someSelected}
                        onSelectAll={handleSelectAll}
                        sortableColumns={sortableColumns}
                        sortBy={sortBy}
                        sortOrder={sortOrder}
                        onSort={handleSort}
                        thClassName={thClassName}
                    />
                    <tbody>
                        {(showSelectAllBanner || selectAllPages) && (
                            <tr>
                                <td
                                    colSpan={totalColSpan}
                                    className="border-b border-neutral-200 bg-muted/50 py-2 text-center text-sm text-neutral-600 dark:border-neutral-800 dark:text-neutral-400"
                                >
                                    {selectAllPages ? (
                                        <>
                                            All{' '}
                                            <span className="font-medium text-neutral-900 dark:text-neutral-100">
                                                {rowCount?.toLocaleString()}
                                            </span>{' '}
                                            {resourceName(true)} are selected.{' '}
                                            <button
                                                type="button"
                                                className="font-medium text-primary hover:underline"
                                                onClick={() => {
                                                    onSelectAllPages?.(false);
                                                    onSelectionChange?.([]);
                                                }}
                                            >
                                                Clear selection
                                            </button>
                                        </>
                                    ) : (
                                        <>
                                            All {data.length}{' '}
                                            {resourceName(data.length !== 1)} on
                                            this page are selected.{' '}
                                            <button
                                                type="button"
                                                className="font-medium text-primary hover:underline"
                                                onClick={() =>
                                                    onSelectAllPages?.(true)
                                                }
                                            >
                                                Select all{' '}
                                                {rowCount?.toLocaleString()}{' '}
                                                {resourceName(true)}
                                            </button>
                                        </>
                                    )}
                                </td>
                            </tr>
                        )}
                        {tableData.map((row, rowIndex) => {
                            const rowId = getRowIdValue(row, rowIndex);

                            return (
                                <DataTableRow
                                    key={rowId}
                                    row={row}
                                    rowIndex={rowIndex}
                                    rowId={rowId}
                                    columns={columns}
                                    isClickable={isClickable}
                                    isSelected={
                                        selectable &&
                                        (selectAllPages ||
                                            selectedIds.includes(rowId))
                                    }
                                    isSelectable={
                                        isRowSelectable?.(row) ?? true
                                    }
                                    onRowClick={onRowClick}
                                    rowActions={rowActions}
                                    tdClassName={tdClassName}
                                    showPlaceholder={showPlaceholder}
                                    selectable={selectable}
                                    onToggleRowSelection={
                                        handleToggleRowSelection
                                    }
                                />
                            );
                        })}
                    </tbody>
                </table>
            </div>

            {pagination && rowCount && rowCount > 0 && (
                <DataTableFooter
                    pagination={pagination}
                    rowCount={rowCount}
                    totalPages={totalPages}
                    onPageChange={onPageChange}
                    onPerPageChange={onPerPageChange}
                    resourceName={resourceName}
                />
            )}

            {loading && (
                <div className="absolute inset-0 flex h-full cursor-wait items-center justify-center bg-white/50 dark:bg-neutral-900/50">
                    <Spinner className="size-6 text-neutral-400" />
                </div>
            )}

            {showPlaceholder && emptyState && (
                <DataTableEmptyState {...emptyState} />
            )}
        </div>
    );
}
