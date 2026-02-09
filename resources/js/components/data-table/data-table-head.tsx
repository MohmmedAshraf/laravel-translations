import type { Column } from '@/components/data-table/data-table.types';
import { Checkbox } from '@/components/ui/checkbox';
import { AltArrowDown, AltArrowUp, SortVertical } from '@/lib/icons';
import { cn } from '@/lib/utils';
import { tableCellClassName } from './data-table.styles';

interface DataTableHeadProps<T> {
    columns: Column<T>[];
    selectable: boolean;
    rowActions: boolean;
    allSelected: boolean;
    someSelected: boolean;
    onSelectAll: () => void;
    sortableColumns: string[];
    sortBy?: string;
    sortOrder?: 'asc' | 'desc';
    onSort: (columnId: string) => void;
    thClassName?: string;
}

export function DataTableHead<T>({
    columns,
    selectable,
    rowActions,
    allSelected,
    someSelected,
    onSelectAll,
    sortableColumns,
    sortBy,
    sortOrder,
    onSort,
    thClassName,
}: DataTableHeadProps<T>) {
    return (
        <thead className="sticky top-0 z-10 bg-white dark:bg-neutral-900">
            <tr>
                {selectable && (
                    <th
                        className={cn(
                            tableCellClassName(),
                            'p-2.5 text-center',
                            thClassName,
                        )}
                        style={{ width: 45 }}
                    >
                        <Checkbox
                            checked={
                                allSelected
                                    ? true
                                    : someSelected
                                      ? 'indeterminate'
                                      : false
                            }
                            className="mx-auto size-5"
                            onCheckedChange={onSelectAll}
                        />
                    </th>
                )}
                {columns.map((column) => {
                    const isSortableColumn = sortableColumns.includes(
                        column.id,
                    );
                    const ButtonOrDiv = isSortableColumn ? 'button' : 'div';

                    return (
                        <th
                            key={column.id}
                            className={cn(
                                tableCellClassName(),
                                'font-medium text-neutral-900 dark:text-neutral-100',
                                thClassName,
                                column.headerClassName,
                            )}
                            style={{
                                minWidth: column.minSize,
                                width: column.maxSize ?? column.size,
                            }}
                        >
                            <div className="flex items-center justify-between gap-2">
                                <ButtonOrDiv
                                    type={
                                        isSortableColumn ? 'button' : undefined
                                    }
                                    className={cn(
                                        'flex items-center gap-2',
                                        isSortableColumn &&
                                            'cursor-pointer hover:text-neutral-700 dark:hover:text-neutral-300',
                                    )}
                                    aria-label={
                                        isSortableColumn
                                            ? 'Sort by column'
                                            : undefined
                                    }
                                    onClick={
                                        isSortableColumn
                                            ? () => onSort(column.id)
                                            : undefined
                                    }
                                >
                                    {column.header}
                                    {isSortableColumn &&
                                        (sortBy === column.id ? (
                                            <SortOrderIcon
                                                order={sortOrder || 'desc'}
                                                className="size-4 shrink-0 text-primary"
                                            />
                                        ) : (
                                            <SortVertical className="size-4 shrink-0 text-neutral-400" />
                                        ))}
                                </ButtonOrDiv>
                            </div>
                        </th>
                    );
                })}
                {rowActions && (
                    <th
                        className={cn(
                            tableCellClassName(),
                            'p-2.5',
                            thClassName,
                        )}
                        style={{ width: 55 }}
                    />
                )}
            </tr>
        </thead>
    );
}

function SortOrderIcon({
    order,
    className,
}: {
    order: 'asc' | 'desc';
    className?: string;
}) {
    return order === 'asc' ? (
        <AltArrowUp className={className} />
    ) : (
        <AltArrowDown className={className} />
    );
}
