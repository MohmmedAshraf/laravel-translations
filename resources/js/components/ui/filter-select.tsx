import type { ReactNode } from 'react';
import { useCallback, useMemo, useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { AltArrowDown, CheckCircle, Filter } from '@/lib/icons';
import { cn } from '@/lib/utils';

export interface FilterOption {
    value: string;
    label: string;
    icon?: ReactNode;
}

export interface Filter {
    key: string;
    label: string;
    icon: ReactNode;
    options: FilterOption[];
    searchable?: boolean;
}

interface ActiveFilter {
    key: string;
    value: string;
}

interface FilterSelectProps {
    filters: Filter[];
    activeFilters: ActiveFilter[];
    onSelect: (key: string, value: string) => void;
    onRemove: (key: string) => void;
    className?: string;
    children?: ReactNode;
    disabled?: boolean;
    iconOnly?: boolean;
}

export function FilterSelect({
    filters,
    activeFilters,
    onSelect,
    onRemove,
    className,
    children,
    disabled,
    iconOnly = false,
}: FilterSelectProps) {
    const [isOpen, setIsOpen] = useState(false);
    const [selectedFilterKey, setSelectedFilterKey] = useState<string | null>(
        null,
    );
    const [search, setSearch] = useState('');

    const selectedFilter = selectedFilterKey
        ? filters.find((f) => f.key === selectedFilterKey)
        : null;

    const handleFilterSelect = useCallback((key: string) => {
        setSelectedFilterKey(key);
    }, []);

    const handleOptionSelect = useCallback(
        (value: string) => {
            if (selectedFilter) {
                const activeFilter = activeFilters.find(
                    (f) => f.key === selectedFilter.key,
                );
                if (activeFilter?.value === value) {
                    onRemove(selectedFilter.key);
                } else {
                    onSelect(selectedFilter.key, value);
                }
                setIsOpen(false);
                setSelectedFilterKey(null);
            }
        },
        [selectedFilter, activeFilters, onSelect, onRemove],
    );

    const handleBack = useCallback(() => {
        setSelectedFilterKey(null);
        setSearch('');
    }, []);

    const handleOpenChange = useCallback((open: boolean) => {
        setIsOpen(open);
        if (!open) {
            setSelectedFilterKey(null);
            setSearch('');
        }
    }, []);

    const isOptionSelected = useCallback(
        (value: string) => {
            if (!selectedFilter) return false;
            const activeFilter = activeFilters.find(
                (f) => f.key === selectedFilter.key,
            );
            return activeFilter?.value === value;
        },
        [selectedFilter, activeFilters],
    );

    const filteredOptions = useMemo(() => {
        if (!selectedFilter) return [];
        if (!search) return selectedFilter.options;
        const q = search.toLowerCase();
        return selectedFilter.options.filter((o) =>
            o.label.toLowerCase().includes(q),
        );
    }, [selectedFilter, search]);

    const iconOnlyTrigger = (
        <Button
            variant="outline"
            size="icon-lg"
            disabled={disabled}
            className={cn('relative', className)}
        >
            <Filter className="size-4" />
            {activeFilters.length > 0 && (
                <span className="absolute -top-1 -right-1 flex size-4 items-center justify-center rounded-full bg-neutral-900 text-[0.625rem] text-white dark:bg-neutral-100 dark:text-neutral-900">
                    {activeFilters.length}
                </span>
            )}
        </Button>
    );

    const defaultTrigger = (
        <button
            type="button"
            disabled={disabled}
            className={cn(
                'group flex h-10 cursor-pointer appearance-none items-center gap-x-2 truncate rounded-md border px-3 text-sm transition-all outline-none',
                'border-neutral-200 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100',
                'hover:border-neutral-300 dark:hover:border-neutral-600',
                'focus:border-neutral-400 focus:ring-2 focus:ring-neutral-100 dark:focus:border-neutral-500 dark:focus:ring-neutral-800',
                disabled &&
                    'cursor-not-allowed opacity-50 hover:border-neutral-200 focus:border-neutral-200 focus:ring-0 dark:hover:border-neutral-700 dark:focus:border-neutral-700',
                className,
            )}
        >
            <Filter className="size-4 shrink-0" />
            <span className="flex-1 overflow-hidden text-left text-ellipsis whitespace-nowrap">
                {children ?? 'Filter'}
            </span>
            {activeFilters.length > 0 ? (
                <div className="flex size-4 shrink-0 items-center justify-center rounded-full bg-neutral-900 text-[0.625rem] text-white dark:bg-neutral-100 dark:text-neutral-900">
                    {activeFilters.length}
                </div>
            ) : (
                <AltArrowDown
                    className={cn(
                        'size-4 shrink-0 text-neutral-400 transition-transform duration-75',
                        isOpen && 'rotate-180',
                    )}
                />
            )}
        </button>
    );

    return (
        <Popover
            open={disabled ? false : isOpen}
            onOpenChange={disabled ? undefined : handleOpenChange}
        >
            {iconOnly ? (
                <TooltipProvider>
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <PopoverTrigger asChild>
                                {iconOnlyTrigger}
                            </PopoverTrigger>
                        </TooltipTrigger>
                        <TooltipContent>Filter</TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            ) : (
                <PopoverTrigger asChild>{defaultTrigger}</PopoverTrigger>
            )}
            <PopoverContent
                align="start"
                className={cn(
                    'p-1',
                    selectedFilter?.searchable ? 'w-56' : 'w-48',
                )}
                sideOffset={4}
            >
                {!selectedFilter ? (
                    <div className="flex flex-col gap-0.5">
                        {filters.map((filter) => (
                            <button
                                key={filter.key}
                                type="button"
                                onClick={() => handleFilterSelect(filter.key)}
                                className="flex w-full cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
                            >
                                <span className="shrink-0 text-neutral-600 dark:text-neutral-400">
                                    {filter.icon}
                                </span>
                                {filter.label}
                            </button>
                        ))}
                    </div>
                ) : (
                    <div className="flex flex-col gap-0.5">
                        <button
                            type="button"
                            onClick={handleBack}
                            className="mb-1 flex w-full cursor-pointer items-center gap-2 border-b border-neutral-200 px-3 py-2 text-left text-xs text-neutral-500 hover:text-neutral-900 dark:border-neutral-700 dark:hover:text-neutral-100"
                        >
                            <AltArrowDown className="size-4 rotate-90" />
                            {selectedFilter.label}
                        </button>
                        {selectedFilter.searchable && (
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search..."
                                className="mx-1 mb-1 rounded-md border border-neutral-200 bg-transparent px-2.5 py-1.5 text-sm outline-none placeholder:text-neutral-400 focus:border-neutral-400 dark:border-neutral-700 dark:placeholder:text-neutral-500 dark:focus:border-neutral-500"
                                autoFocus
                            />
                        )}
                        <div className="max-h-64 overflow-y-auto">
                            {filteredOptions.map((option) => {
                                const isSelected = isOptionSelected(
                                    option.value,
                                );
                                return (
                                    <button
                                        key={option.value}
                                        type="button"
                                        onClick={() =>
                                            handleOptionSelect(option.value)
                                        }
                                        className={cn(
                                            'flex w-full cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-left text-sm',
                                            'hover:bg-neutral-100 dark:hover:bg-neutral-700',
                                            isSelected &&
                                                'bg-neutral-50 dark:bg-neutral-800',
                                        )}
                                    >
                                        <span className="shrink-0 text-neutral-600 dark:text-neutral-400">
                                            {option.icon}
                                        </span>
                                        <span className="flex-1">
                                            {option.label}
                                        </span>
                                        {isSelected && (
                                            <CheckCircle className="size-4 text-neutral-900 dark:text-neutral-100" />
                                        )}
                                    </button>
                                );
                            })}
                            {selectedFilter.searchable &&
                                search &&
                                filteredOptions.length === 0 && (
                                    <div className="px-3 py-2 text-sm text-neutral-400">
                                        No results
                                    </div>
                                )}
                        </div>
                    </div>
                )}
            </PopoverContent>
        </Popover>
    );
}

interface FilterListProps {
    filters: Filter[];
    activeFilters: ActiveFilter[];
    onRemove: (key: string) => void;
    onRemoveAll: () => void;
}

export function FilterList({
    filters,
    activeFilters,
    onRemove,
    onRemoveAll,
}: FilterListProps) {
    if (activeFilters.length === 0) return null;

    return (
        <div className="flex flex-wrap items-center gap-4">
            <div className="flex grow flex-wrap gap-2">
                {activeFilters.map(({ key, value }) => {
                    const filter = filters.find((f) => f.key === key);
                    if (!filter) return null;

                    const option = filter.options.find(
                        (o) => o.value === value,
                    );
                    if (!option) return null;

                    return (
                        <div
                            key={`${key}-${value}`}
                            className="flex items-center divide-x divide-neutral-200 rounded-md border border-neutral-200 bg-white text-sm text-neutral-900 dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                        >
                            <div className="flex items-center gap-2 px-3 py-1.5">
                                <span className="shrink-0 text-neutral-600 dark:text-neutral-400">
                                    {filter.icon}
                                </span>
                                {filter.label}
                            </div>
                            <div className="px-3 py-1.5 text-neutral-500">
                                is
                            </div>
                            <div className="flex items-center gap-2 px-3 py-1.5">
                                {option.icon && (
                                    <span className="shrink-0 text-neutral-600 dark:text-neutral-400">
                                        {option.icon}
                                    </span>
                                )}
                                {option.label}
                            </div>
                            <button
                                type="button"
                                onClick={() => onRemove(key)}
                                className="h-full rounded-r-md px-2 py-1.5 text-neutral-500 hover:bg-neutral-100 hover:text-neutral-800 dark:hover:bg-neutral-700 dark:hover:text-neutral-200"
                            >
                                <svg
                                    className="size-3.5"
                                    viewBox="0 0 14 14"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 3L3 11M3 3L11 11"
                                        stroke="currentColor"
                                        strokeWidth="1.5"
                                        strokeLinecap="round"
                                    />
                                </svg>
                            </button>
                        </div>
                    );
                })}
            </div>

            <Button
                variant="ghost"
                size="sm"
                onClick={onRemoveAll}
                className="text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-100"
            >
                Clear filters
                <kbd className="ml-2 rounded border border-neutral-200 bg-neutral-100 px-1.5 py-0.5 text-xs dark:border-neutral-700 dark:bg-neutral-800">
                    ESC
                </kbd>
            </Button>
        </div>
    );
}
