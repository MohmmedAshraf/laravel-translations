import type { ReactNode } from 'react';
import type { Column } from '@/components/data-table/data-table.types';
import { Checkbox } from '@/components/ui/checkbox';
import { cn } from '@/lib/utils';
import { tableCellClassName } from './data-table.styles';

interface DataTableRowProps<T> {
    row: T;
    rowIndex: number;
    rowId: string | number;
    columns: Column<T>[];
    isClickable: boolean;
    isSelected: boolean;
    onRowClick?: (row: T, index: number) => void;
    rowActions?: (row: T, index: number) => ReactNode;
    tdClassName?: string;
    showPlaceholder: boolean;
    selectable: boolean;
    isSelectable: boolean;
    onToggleRowSelection: (rowId: string | number) => void;
}

export function DataTableRow<T>({
    row,
    rowIndex,
    rowId,
    columns,
    isClickable,
    isSelected,
    onRowClick,
    rowActions,
    tdClassName,
    showPlaceholder,
    selectable,
    isSelectable,
    onToggleRowSelection,
}: DataTableRowProps<T>) {
    return (
        <tr
            className={cn(
                'group/row',
                isClickable && 'cursor-pointer select-none',
                isSelected && 'bg-muted/50',
            )}
            onClick={
                isClickable ? () => onRowClick?.(row, rowIndex) : undefined
            }
        >
            {selectable && !showPlaceholder && (
                <td
                    className={cn(
                        tableCellClassName(),
                        'p-2.5 text-center',
                        tdClassName,
                    )}
                    style={{ width: 45 }}
                >
                    <Checkbox
                        checked={isSelected}
                        disabled={!isSelectable}
                        className="mx-auto size-5"
                        onClick={(event) => event.stopPropagation()}
                        onCheckedChange={() => onToggleRowSelection(rowId)}
                    />
                </td>
            )}
            {selectable && showPlaceholder && (
                <td
                    className={cn(tableCellClassName(), 'p-2.5', tdClassName)}
                    style={{ width: 45 }}
                />
            )}
            {columns.map((column) => (
                <td
                    key={column.id}
                    className={cn(
                        tableCellClassName(),
                        'text-neutral-700 dark:text-neutral-300',
                        tdClassName,
                        column.cellClassName,
                    )}
                    style={{
                        minWidth: column.minSize,
                        width: column.maxSize ?? column.size,
                    }}
                >
                    <div className="flex w-full items-center justify-between truncate overflow-hidden">
                        <div className="min-w-0 shrink grow truncate">
                            {column.cell
                                ? column.cell(row, rowIndex)
                                : column.accessorKey
                                  ? String(row[column.accessorKey] ?? '')
                                  : null}
                        </div>
                    </div>
                </td>
            ))}
            {rowActions && !showPlaceholder && (
                <td
                    className={cn(tableCellClassName(), 'p-2.5 text-center')}
                    style={{ width: 55 }}
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="flex items-center justify-center">
                        {rowActions(row, rowIndex)}
                    </div>
                </td>
            )}
        </tr>
    );
}
