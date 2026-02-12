import { useCallback } from 'react';

interface UseDataTableSortingParams {
    sortBy?: string;
    sortOrder?: 'asc' | 'desc';
    onSortChange?: (params: {
        sortBy: string;
        sortOrder: 'asc' | 'desc';
    }) => void;
    sortableColumns: string[];
}

export function useDataTableSorting({
    sortBy,
    sortOrder,
    onSortChange,
    sortableColumns,
}: UseDataTableSortingParams): (columnId: string) => void {
    return useCallback(
        (columnId: string) => {
            if (!onSortChange || !sortableColumns.includes(columnId)) {
                return;
            }

            const newOrder: 'asc' | 'desc' =
                sortBy !== columnId
                    ? 'desc'
                    : sortOrder === 'asc'
                      ? 'desc'
                      : 'asc';

            onSortChange({ sortBy: columnId, sortOrder: newOrder });
        },
        [onSortChange, sortBy, sortOrder, sortableColumns],
    );
}
