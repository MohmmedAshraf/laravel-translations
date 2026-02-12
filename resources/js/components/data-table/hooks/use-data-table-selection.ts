import { useCallback, useMemo } from 'react';

interface UseDataTableSelectionParams<T> {
    data: T[];
    selectable: boolean;
    selectedIds: (string | number)[];
    onSelectionChange?: (ids: (string | number)[]) => void;
    getRowIdValue: (row: T, index: number) => string | number;
    isRowSelectable?: (row: T) => boolean;
}

interface UseDataTableSelectionResult {
    allRowIds: (string | number)[];
    selectableRowIds: (string | number)[];
    allSelected: boolean;
    someSelected: boolean;
    handleSelectAll: () => void;
    handleToggleRowSelection: (rowId: string | number) => void;
}

export function useDataTableSelection<T>({
    data,
    selectable,
    selectedIds,
    onSelectionChange,
    getRowIdValue,
    isRowSelectable,
}: UseDataTableSelectionParams<T>): UseDataTableSelectionResult {
    const allRowIds = useMemo(
        () => data.map((row, index) => getRowIdValue(row, index)),
        [data, getRowIdValue],
    );

    const selectableRowIds = useMemo(
        () =>
            isRowSelectable
                ? data
                      .filter((row) => isRowSelectable(row))
                      .map((row) => getRowIdValue(row, data.indexOf(row)))
                : allRowIds,
        [data, allRowIds, isRowSelectable, getRowIdValue],
    );

    const allSelected =
        selectable &&
        selectableRowIds.length > 0 &&
        selectableRowIds.every((id) => selectedIds.includes(id));
    const someSelected = selectable && selectedIds.length > 0 && !allSelected;

    const handleSelectAll = useCallback(() => {
        if (!onSelectionChange) {
            return;
        }

        if (allSelected) {
            onSelectionChange([]);
            return;
        }

        onSelectionChange(selectableRowIds);
    }, [selectableRowIds, allSelected, onSelectionChange]);

    const handleToggleRowSelection = useCallback(
        (rowId: string | number) => {
            if (!onSelectionChange) {
                return;
            }

            if (selectedIds.includes(rowId)) {
                onSelectionChange(selectedIds.filter((id) => id !== rowId));
                return;
            }

            onSelectionChange([...selectedIds, rowId]);
        },
        [onSelectionChange, selectedIds],
    );

    return {
        allRowIds,
        selectableRowIds,
        allSelected,
        someSelected,
        handleSelectAll,
        handleToggleRowSelection,
    };
}
