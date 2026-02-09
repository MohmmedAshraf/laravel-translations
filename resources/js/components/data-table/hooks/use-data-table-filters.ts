import { router } from '@inertiajs/react';
import { useCallback, useEffect, useMemo, useState } from 'react';

interface FilterParams {
    search?: string;
    filter?: Record<string, string>;
    sort?: string;
    page?: number;
    per_page?: number;
}

interface UseDataTableFiltersOptions {
    route: string;
    initialFilters: FilterParams;
    only?: string[];
}

interface ActiveFilter {
    key: string;
    value: string;
}

export function useDataTableFilters({
    route,
    initialFilters,
    only = ['data', 'filters'],
}: UseDataTableFiltersOptions) {
    const searchFromFilter = initialFilters.filter?.search;
    const [prevSearchProp, setPrevSearchProp] = useState(searchFromFilter);
    const [search, setSearch] = useState(searchFromFilter ?? '');
    const [isLoading, setIsLoading] = useState(false);

    if (searchFromFilter !== prevSearchProp) {
        setPrevSearchProp(searchFromFilter);
        setSearch(searchFromFilter ?? '');
    }

    const activeFilters = useMemo((): ActiveFilter[] => {
        if (!initialFilters.filter) return [];
        return Object.entries(initialFilters.filter)
            .filter(([key]) => key !== 'search')
            .map(([key, value]) => ({
                key,
                value,
            }));
    }, [initialFilters.filter]);

    const sortBy = useMemo(() => {
        const sort = initialFilters.sort;
        if (!sort) return undefined;
        return sort.startsWith('-') ? sort.slice(1) : sort;
    }, [initialFilters.sort]);

    const sortOrder = useMemo((): 'asc' | 'desc' => {
        const sort = initialFilters.sort;
        if (!sort) return 'desc';
        return sort.startsWith('-') ? 'desc' : 'asc';
    }, [initialFilters.sort]);

    const isFiltered =
        activeFilters.length > 0 || !!initialFilters.filter?.search;

    const navigate = useCallback(
        (params: Record<string, unknown>) => {
            setIsLoading(true);
            router.get(route, params as Record<string, string>, {
                preserveState: true,
                preserveScroll: true,
                only,
                onFinish: () => setIsLoading(false),
            });
        },
        [route, only],
    );

    const handleSearchChange = useCallback((value: string) => {
        setSearch(value);
    }, []);

    const handleSearchDebounced = useCallback(
        (value: string) => {
            const params: Record<string, unknown> = { ...initialFilters };
            delete params.search;

            if (value) {
                params.filter = {
                    ...(initialFilters.filter ?? {}),
                    search: value,
                };
            } else {
                const restFilter = {
                    ...(initialFilters.filter ?? {}),
                } as Record<string, string>;
                delete restFilter.search;
                params.filter =
                    Object.keys(restFilter).length > 0 ? restFilter : undefined;
            }
            delete params.page;
            navigate(params);
        },
        [initialFilters, navigate],
    );

    const handleFilterSelect = useCallback(
        (key: string, value: string) => {
            const newFilter = { ...initialFilters.filter, [key]: value };
            navigate({
                ...initialFilters,
                filter: newFilter,
                page: undefined,
            });
        },
        [initialFilters, navigate],
    );

    const handleFilterRemove = useCallback(
        (key: string) => {
            const newFilter = { ...initialFilters.filter };
            delete newFilter[key];
            navigate({
                ...initialFilters,
                filter:
                    Object.keys(newFilter).length > 0 ? newFilter : undefined,
                page: undefined,
            });
        },
        [initialFilters, navigate],
    );

    const handleFilterRemoveAll = useCallback(() => {
        navigate({
            sort: initialFilters.sort,
        });
    }, [initialFilters.sort, navigate]);

    const handleSortChange = useCallback(
        (params: { sortBy: string; sortOrder: 'asc' | 'desc' }) => {
            const sortValue =
                params.sortOrder === 'desc'
                    ? `-${params.sortBy}`
                    : params.sortBy;
            navigate({
                ...initialFilters,
                sort: sortValue,
            });
        },
        [initialFilters, navigate],
    );

    const handlePageChange = useCallback(
        (page: number) => {
            navigate({
                ...initialFilters,
                page,
            });
        },
        [initialFilters, navigate],
    );

    const handlePerPageChange = useCallback(
        (perPage: number) => {
            navigate({
                ...initialFilters,
                per_page: perPage,
                page: undefined,
            });
        },
        [initialFilters, navigate],
    );

    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent) => {
            const tag = (e.target as HTMLElement)?.tagName;
            if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') {
                return;
            }
            if (e.key === 'Escape' && activeFilters.length > 0) {
                handleFilterRemoveAll();
            }
        };

        document.addEventListener('keydown', handleKeyDown);
        return () => document.removeEventListener('keydown', handleKeyDown);
    }, [activeFilters.length, handleFilterRemoveAll]);

    return {
        search,
        isLoading,
        activeFilters,
        sortBy,
        sortOrder,
        isFiltered,
        handleSearchChange,
        handleSearchDebounced,
        handleFilterSelect,
        handleFilterRemove,
        handleFilterRemoveAll,
        handleSortChange,
        handlePageChange,
        handlePerPageChange,
    };
}
