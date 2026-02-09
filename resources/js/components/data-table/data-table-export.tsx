import { useCallback, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { DownloadMinimalistic } from '@/lib/icons';

interface ExportColumn {
    id: string;
    header: string;
    accessorFn?: (
        row: Record<string, unknown>,
    ) => string | number | boolean | null;
}

interface DataTableExportProps<T> {
    data: T[];
    columns: ExportColumn[];
    filename?: string;
    serverExport?: boolean;
    disabled?: boolean;
    iconOnly?: boolean;
}

export function DataTableExport<T extends Record<string, unknown>>({
    data,
    columns,
    filename = 'export',
    serverExport = false,
    disabled = false,
    iconOnly = false,
}: DataTableExportProps<T>) {
    const [isExporting, setIsExporting] = useState(false);

    const isDisabled = disabled || (data.length === 0 && !serverExport);

    const getCellValue = useCallback((row: T, column: ExportColumn): string => {
        if (column.accessorFn) {
            const value = column.accessorFn(row);
            return value === null || value === undefined ? '' : String(value);
        }
        const value = row[column.id];
        if (value === null || value === undefined) return '';
        if (typeof value === 'object') return JSON.stringify(value);
        return String(value);
    }, []);

    const exportToCSV = useCallback(() => {
        const headers = columns.map((col) => col.header);
        const rows = data.map((row) =>
            columns.map((col) => {
                const value = getCellValue(row, col);
                if (
                    value.includes(',') ||
                    value.includes('"') ||
                    value.includes('\n')
                ) {
                    return `"${value.replace(/"/g, '""')}"`;
                }
                return value;
            }),
        );

        const csv = [
            headers.join(','),
            ...rows.map((row) => row.join(',')),
        ].join('\n');

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `${filename}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }, [data, columns, filename, getCellValue]);

    const exportFromServer = useCallback(async () => {
        setIsExporting(true);
        try {
            const url = new URL(
                window.location.pathname + '/export',
                window.location.origin,
            );
            url.searchParams.set('format', 'csv');

            const currentParams = new URLSearchParams(window.location.search);
            currentParams.forEach((value, key) => {
                if (key !== 'page') {
                    url.searchParams.set(key, value);
                }
            });

            const response = await fetch(url.toString(), {
                headers: {
                    Accept: 'text/csv',
                },
            });

            if (!response.ok) {
                throw new Error('Export failed');
            }

            const blob = await response.blob();
            const downloadUrl = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = `${filename}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(downloadUrl);
        } catch (error) {
            console.error('Server export failed:', error);
        } finally {
            setIsExporting(false);
        }
    }, [filename]);

    const handleExport = useCallback(() => {
        if (serverExport) {
            exportFromServer();
        } else {
            exportToCSV();
        }
    }, [serverExport, exportFromServer, exportToCSV]);

    const trigger = (
        <Button
            variant="outline"
            size={iconOnly ? 'icon-lg' : 'lg'}
            disabled={isExporting || isDisabled}
            onClick={handleExport}
        >
            {isExporting ? (
                <Spinner className="size-4" />
            ) : (
                <DownloadMinimalistic className="size-4" />
            )}
            {!iconOnly && (isExporting ? 'Exporting...' : 'Export')}
        </Button>
    );

    if (iconOnly) {
        return (
            <TooltipProvider>
                <Tooltip>
                    <TooltipTrigger asChild>{trigger}</TooltipTrigger>
                    <TooltipContent>Export</TooltipContent>
                </Tooltip>
            </TooltipProvider>
        );
    }

    return trigger;
}
