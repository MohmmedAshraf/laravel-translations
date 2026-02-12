import type { ReactNode } from 'react';
import type { EmptyStateConfig } from '@/components/data-table/data-table-empty-state';
import type { IconComponent } from '@/lib/icons';

export type ColumnType =
    | 'text'
    | 'mono'
    | 'badge'
    | 'date'
    | 'datetime'
    | 'list'
    | 'boolean'
    | 'progress'
    | 'language'
    | 'relative-time'
    | 'status-icon'
    | 'custom';

export interface BadgeVariant {
    variant: 'default' | 'secondary' | 'destructive' | 'outline';
    color?: string;
    label?: string;
    icon?: string;
}

export interface TableColumnConfig {
    id: string;
    header: string;
    accessorKey?: string;
    type?: ColumnType;
    sortable?: boolean;
    searchable?: boolean;
    canHide?: boolean;
    visible?: boolean;
    minSize?: number;
    maxSize?: number;
    badgeMap?: Record<string, BadgeVariant>;
    dateFormat?: Intl.DateTimeFormatOptions;
    prefix?: string;
    suffix?: string;
    truncate?: number;
    emptyValue?: string;
    fill?: boolean;
    cellClassName?: string;
    headerClassName?: string;
    headerIcon?: string;
}

export interface FilterOptionConfig {
    value: string;
    label: string;
    icon?: string;
    iconColor?: string;
}

export interface FilterConfig {
    key: string;
    label: string;
    icon?: string;
    options: FilterOptionConfig[];
    searchable?: boolean;
}

export interface BulkActionConfig {
    name: string;
    label: string;
    icon?: string;
    url: string | null;
    confirm?: string;
    confirmTitle?: string;
    variant?: 'default' | 'destructive';
    download?: boolean;
}

export interface TableConfig {
    columns: TableColumnConfig[];
    filters: FilterConfig[];
    sortableColumns: string[];
    searchColumn: string;
    resourceName: [string, string];
    bulkActions?: BulkActionConfig[];
    currentFilters: {
        search?: string;
        filter?: Record<string, string>;
        sort?: string;
    };
}

export interface PaginatedData<T> {
    data: T[];
    total: number;
    current_page: number;
    per_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
}

export interface RowAction<T> {
    label: string;
    icon: IconComponent;
    onClick: (row: T, event?: React.MouseEvent) => void;
    variant?: 'default' | 'destructive';
    disabled?: boolean | ((row: T) => boolean);
    hidden?: boolean | ((row: T) => boolean);
}

export type CustomCellRenderer<T> = (row: T, index: number) => ReactNode;

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export interface AdminDataTableProps<T extends Record<string, any>> {
    data: PaginatedData<T>;
    tableConfig: TableConfig;
    route: string;
    placeholderData?: T[];
    emptyState?: EmptyStateConfig;
    onRowClick?: (row: T) => void;
    rowActions?: RowAction<T>[];
    getRowId?: (row: T) => string | number;
    toolbarActions?: ReactNode;
    columnStorageKey?: string;
    exportFilename?: string;
    serverExport?: boolean;
    customCellRenderers?: Record<string, CustomCellRenderer<T>>;
    compactToolbar?: boolean;
    isRowSelectable?: (row: T) => boolean;
    onBulkAction?: (name: string, ids: (string | number)[]) => void;
}
