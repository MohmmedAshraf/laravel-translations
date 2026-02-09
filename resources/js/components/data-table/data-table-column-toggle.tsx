import { useCallback, useEffect, useMemo, useRef } from 'react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { Restart, Widget5 } from '@/lib/icons';

export interface ColumnConfig {
    id: string;
    label: string;
    visible: boolean;
    canHide?: boolean;
}

interface DataTableColumnToggleProps {
    columns: ColumnConfig[];
    onChange: (columns: ColumnConfig[]) => void;
    storageKey?: string;
    disabled?: boolean;
    iconOnly?: boolean;
    defaultColumns?: ColumnConfig[];
}

export function DataTableColumnToggle({
    columns,
    onChange,
    storageKey,
    disabled = false,
    iconOnly = false,
    defaultColumns,
}: DataTableColumnToggleProps) {
    const hasInitialized = useRef(false);
    const columnsRef = useRef(columns);
    const onChangeRef = useRef(onChange);

    useEffect(() => {
        columnsRef.current = columns;
        onChangeRef.current = onChange;
    });

    useEffect(() => {
        if (!storageKey || hasInitialized.current) return;

        const saved = localStorage.getItem(storageKey);
        if (saved) {
            try {
                const savedVisibility = JSON.parse(saved) as Record<
                    string,
                    boolean
                >;
                const updatedColumns = columnsRef.current.map((col) => ({
                    ...col,
                    visible:
                        savedVisibility[col.id] !== undefined
                            ? savedVisibility[col.id]
                            : col.visible,
                }));
                onChangeRef.current(updatedColumns);
            } catch {
                // Invalid saved data, ignore
            }
        }
        hasInitialized.current = true;
    }, [storageKey]);

    const handleToggle = useCallback(
        (columnId: string) => {
            const updatedColumns = columns.map((col) =>
                col.id === columnId ? { ...col, visible: !col.visible } : col,
            );
            onChange(updatedColumns);

            if (storageKey) {
                const visibility = updatedColumns.reduce(
                    (acc, col) => {
                        acc[col.id] = col.visible;
                        return acc;
                    },
                    {} as Record<string, boolean>,
                );
                localStorage.setItem(storageKey, JSON.stringify(visibility));
            }
        },
        [columns, onChange, storageKey],
    );

    const handleReset = useCallback(() => {
        if (!defaultColumns) return;

        onChange(defaultColumns);

        if (storageKey) {
            localStorage.removeItem(storageKey);
        }
    }, [defaultColumns, onChange, storageKey]);

    const isModified = useMemo(() => {
        if (!defaultColumns) return false;
        return columns.some((col) => {
            const defaultCol = defaultColumns.find((d) => d.id === col.id);
            return defaultCol && col.visible !== defaultCol.visible;
        });
    }, [columns, defaultColumns]);

    const visibleCount = columns.filter((c) => c.visible).length;

    const trigger = (
        <Button
            variant="outline"
            size={iconOnly ? 'icon-lg' : 'lg'}
            disabled={disabled}
        >
            <Widget5 className="size-4" />
            {!iconOnly && (
                <>
                    Columns
                    <span className="ml-1.5 rounded bg-neutral-100 px-1.5 py-0.5 text-xs dark:bg-neutral-800">
                        {visibleCount}
                    </span>
                </>
            )}
        </Button>
    );

    return (
        <DropdownMenu>
            {iconOnly ? (
                <TooltipProvider>
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <DropdownMenuTrigger asChild>
                                {trigger}
                            </DropdownMenuTrigger>
                        </TooltipTrigger>
                        <TooltipContent>
                            {visibleCount} columns visible
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            ) : (
                <DropdownMenuTrigger asChild>{trigger}</DropdownMenuTrigger>
            )}
            <DropdownMenuContent align="end" className="w-48" sideOffset={4}>
                <DropdownMenuGroup>
                    <DropdownMenuLabel className="text-xs text-neutral-500">
                        Toggle columns
                    </DropdownMenuLabel>
                    {columns
                        .filter(
                            (column) =>
                                column.canHide !== false || column.label,
                        )
                        .map((column) => {
                            const canToggle = column.canHide !== false;

                            return (
                                <DropdownMenuCheckboxItem
                                    key={column.id}
                                    checked={column.visible}
                                    disabled={!canToggle}
                                    onSelect={(event) => event.preventDefault()}
                                    onCheckedChange={() =>
                                        canToggle && handleToggle(column.id)
                                    }
                                >
                                    {column.label}
                                </DropdownMenuCheckboxItem>
                            );
                        })}
                </DropdownMenuGroup>
                {isModified && (
                    <>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem
                            onSelect={handleReset}
                            className="text-xs text-neutral-500"
                        >
                            <Restart className="size-4" />
                            Reset to defaults
                        </DropdownMenuItem>
                    </>
                )}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
