import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { DoubleAltArrowLeft, DoubleAltArrowRight } from '@/lib/icons';

const DEFAULT_PER_PAGE_OPTIONS = [10, 15, 25, 50, 100];

interface DataTableFooterProps {
    pagination: {
        pageIndex: number;
        pageSize: number;
    };
    rowCount: number;
    totalPages: number;
    onPageChange?: (page: number) => void;
    onPerPageChange?: (perPage: number) => void;
    perPageOptions?: number[];
    resourceName: (plural: boolean) => string;
}

export function DataTableFooter({
    pagination,
    rowCount,
    totalPages,
    onPageChange,
    onPerPageChange,
    perPageOptions = DEFAULT_PER_PAGE_OPTIONS,
    resourceName,
}: DataTableFooterProps) {
    return (
        <div className="sticky bottom-0 mx-auto -mt-px flex w-full max-w-full items-center justify-between rounded-b-xl border-t border-neutral-200 bg-white px-4 py-3.5 text-sm leading-6 text-neutral-900 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-100">
            <div className="flex items-center gap-4">
                {onPerPageChange && (
                    <div className="flex items-center gap-2">
                        <span className="hidden text-neutral-500 sm:inline-block dark:text-neutral-400">
                            Show
                        </span>
                        <Select
                            value={String(pagination.pageSize)}
                            onValueChange={(value) =>
                                onPerPageChange(Number(value))
                            }
                        >
                            <SelectTrigger className="h-7 w-[70px]">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {perPageOptions.map((option) => (
                                    <SelectItem
                                        key={option}
                                        value={String(option)}
                                    >
                                        {option}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                )}
                <div>
                    <span className="hidden sm:inline-block">Viewing</span>{' '}
                    <span className="font-medium">
                        {(
                            (pagination.pageIndex - 1) * pagination.pageSize +
                            1
                        ).toLocaleString()}
                        -
                        {Math.min(
                            pagination.pageIndex * pagination.pageSize,
                            rowCount,
                        ).toLocaleString()}
                    </span>{' '}
                    of{' '}
                    <span className="font-medium">
                        {rowCount.toLocaleString()}
                    </span>{' '}
                    {resourceName(rowCount !== 1)}
                </div>
            </div>
            <div className="flex items-center gap-2">
                {totalPages > 2 && (
                    <Button
                        variant="outline"
                        size="sm"
                        className="h-7 px-1.5"
                        onClick={() => onPageChange?.(1)}
                        disabled={pagination.pageIndex <= 1}
                    >
                        <DoubleAltArrowLeft className="size-4" />
                    </Button>
                )}
                <Button
                    variant="outline"
                    size="sm"
                    className="h-7 px-2"
                    onClick={() => onPageChange?.(pagination.pageIndex - 1)}
                    disabled={pagination.pageIndex <= 1}
                >
                    Previous
                </Button>
                <Button
                    variant="outline"
                    size="sm"
                    className="h-7 px-2"
                    onClick={() => onPageChange?.(pagination.pageIndex + 1)}
                    disabled={pagination.pageIndex >= totalPages}
                >
                    Next
                </Button>
                {totalPages > 2 && (
                    <Button
                        variant="outline"
                        size="sm"
                        className="h-7 px-1.5"
                        onClick={() => onPageChange?.(totalPages)}
                        disabled={pagination.pageIndex >= totalPages}
                    >
                        <DoubleAltArrowRight className="size-4" />
                    </Button>
                )}
            </div>
        </div>
    );
}
