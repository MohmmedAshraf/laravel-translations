import type { ReactNode } from 'react';
import type { EmptyStateConfig } from '@/components/data-table/data-table-empty-state';

export interface Column<T> {
    id: string;
    header: string | ReactNode;
    accessorKey?: keyof T;
    cell?: (row: T, index: number) => ReactNode;
    minSize?: number;
    size?: number | 'auto';
    maxSize?: number;
    fill?: boolean;
    headerClassName?: string;
    cellClassName?: string;
}

export interface Pagination {
    pageIndex: number;
    pageSize: number;
}

export interface DataTableProps<T> {
    data: T[];
    columns: Column<T>[];
    loading?: boolean;
    error?: string;
    placeholderData?: T[];
    emptyState?: EmptyStateConfig;
    isFiltered?: boolean;
    onClearFilters?: () => void;
    sortBy?: string;
    sortOrder?: 'asc' | 'desc';
    onSortChange?: (params: {
        sortBy: string;
        sortOrder: 'asc' | 'desc';
    }) => void;
    sortableColumns?: string[];
    pagination?: Pagination;
    rowCount?: number;
    onPageChange?: (page: number) => void;
    onPerPageChange?: (perPage: number) => void;
    resourceName?: (plural: boolean) => string;
    onRowClick?: (row: T, index: number) => void;
    rowActions?: (row: T, index: number) => ReactNode;
    getRowId?: (row: T) => string | number;
    className?: string;
    containerClassName?: string;
    thClassName?: string;
    tdClassName?: string;
    selectable?: boolean;
    selectedIds?: (string | number)[];
    onSelectionChange?: (ids: (string | number)[]) => void;
    isRowSelectable?: (row: T) => boolean;
    selectAllPages?: boolean;
    onSelectAllPages?: (value: boolean) => void;
}
