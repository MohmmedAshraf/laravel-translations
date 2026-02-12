import type { ReactNode } from 'react';
import { SearchBox } from '@/components/ui/search-box';
import { cn } from '@/lib/utils';

interface DataTableToolbarProps {
    search?: string;
    searchLoading?: boolean;
    onSearchChange?: (value: string) => void;
    onSearchChangeDebounced?: (value: string) => void;
    searchPlaceholder?: string;
    searchDisabled?: boolean;
    filters?: ReactNode;
    actions?: ReactNode;
    className?: string;
}

export function DataTableToolbar({
    search,
    searchLoading,
    onSearchChange,
    onSearchChangeDebounced,
    searchPlaceholder = 'Search...',
    searchDisabled,
    filters,
    actions,
    className,
}: DataTableToolbarProps) {
    const hasSearch = onSearchChange !== undefined;

    return (
        <div
            className={cn(
                'grid gap-3',
                'grid-cols-1',
                'sm:grid-cols-[1fr_auto]',
                className,
            )}
        >
            <div className="flex items-center gap-2">
                {filters}
                {hasSearch && (
                    <div className="min-w-0 flex-1 sm:max-w-xs">
                        <SearchBox
                            value={search ?? ''}
                            loading={searchLoading}
                            onChange={onSearchChange}
                            onChangeDebounced={onSearchChangeDebounced}
                            placeholder={searchPlaceholder}
                            disabled={searchDisabled}
                        />
                    </div>
                )}
            </div>
            {actions && (
                <div
                    className={cn(
                        'grid items-center gap-2',
                        'grid-cols-2 [&>*:last-child:nth-child(odd)]:col-span-2',
                        'sm:flex sm:grid-cols-none',
                    )}
                >
                    {actions}
                </div>
            )}
        </div>
    );
}
