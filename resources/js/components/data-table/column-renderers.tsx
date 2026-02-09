import type { ReactNode } from 'react';
import { Badge } from '@/components/ui/badge';
import { Flag } from '@/components/ui/flag';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { CheckCircle } from '@/lib/icons';
import type { BadgeVariant, TableColumnConfig } from './admin-data-table.types';
import { renderIcon } from './icon-map';

export function getNestedValue<T extends Record<string, unknown>>(
    obj: T,
    path: string,
): unknown {
    return path.split('.').reduce<unknown>((acc, part) => {
        if (acc && typeof acc === 'object' && part in acc) {
            return (acc as Record<string, unknown>)[part];
        }
        return undefined;
    }, obj);
}

const DEFAULT_DATE_FORMAT: Intl.DateTimeFormatOptions = {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
};

const DEFAULT_DATETIME_FORMAT: Intl.DateTimeFormatOptions = {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
};

export function formatDate(
    value: string | null | undefined,
    format: Intl.DateTimeFormatOptions = DEFAULT_DATE_FORMAT,
    emptyValue = 'Never',
): string {
    if (!value) return emptyValue;
    return new Intl.DateTimeFormat('en-US', format).format(new Date(value));
}

function ProgressCell({ row }: { row: Record<string, unknown> }) {
    const isSource = row.is_source as boolean | undefined;
    const translated = Number(row.translated_count ?? 0);
    const untranslated = Number(row.untranslated_count ?? 0);
    const needsReview = Number(row.needs_review_count ?? 0);
    const total = translated + untranslated + needsReview;
    const percentage = Number(row.progress ?? 0);

    if (isSource) {
        const totalKeys = Number(row.total_keys ?? 0);
        return (
            <span className="text-sm text-neutral-500 dark:text-neutral-400">
                {totalKeys} {totalKeys === 1 ? 'key' : 'keys'}
            </span>
        );
    }

    const translatedPct = total > 0 ? (translated / total) * 100 : 0;
    const needsReviewPct = total > 0 ? (needsReview / total) * 100 : 0;

    return (
        <div className="flex w-full items-center gap-3">
            <div className="flex h-2 min-w-24 flex-1 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                {translatedPct > 0 && (
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <div
                                className="h-full bg-green-500 transition-all duration-300"
                                style={{ width: `${translatedPct}%` }}
                            />
                        </TooltipTrigger>
                        <TooltipContent>
                            Translated: {translated}
                        </TooltipContent>
                    </Tooltip>
                )}
                {needsReviewPct > 0 && (
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <div
                                className="h-full bg-amber-400 transition-all duration-300"
                                style={{ width: `${needsReviewPct}%` }}
                            />
                        </TooltipTrigger>
                        <TooltipContent>
                            Needs review: {needsReview}
                        </TooltipContent>
                    </Tooltip>
                )}
            </div>
            <Tooltip>
                <TooltipTrigger asChild>
                    <span className="shrink-0 cursor-default text-xs text-neutral-500 tabular-nums dark:text-neutral-400">
                        {percentage}%
                    </span>
                </TooltipTrigger>
                <TooltipContent>
                    <div className="flex flex-col gap-1 text-xs">
                        <div className="flex items-center gap-2">
                            <span className="size-2 rounded-full bg-green-500" />
                            <span>Translated: {translated}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <span className="size-2 rounded-full bg-amber-400" />
                            <span>Needs review: {needsReview}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <span className="size-2 rounded-full bg-neutral-300 dark:bg-neutral-600" />
                            <span>Untranslated: {untranslated}</span>
                        </div>
                    </div>
                </TooltipContent>
            </Tooltip>
        </div>
    );
}

function LanguageCell({
    row,
    column,
}: {
    row: Record<string, unknown>;
    column?: TableColumnConfig;
}) {
    const key = column?.accessorKey ?? column?.id;
    const value = key ? row[key] : null;
    const source =
        typeof value === 'object' && value !== null
            ? (value as Record<string, unknown>)
            : row;

    const name = typeof source.name === 'string' ? source.name : '';
    const code = typeof source.code === 'string' ? source.code : '';
    const isSource =
        typeof source.is_source === 'boolean' ? source.is_source : undefined;
    const progress =
        Number(row.progress ?? source.progress ?? undefined) || undefined;
    const isFullyTranslated = !isSource && progress === 100;

    return (
        <div className="flex items-center gap-2.5">
            <Flag code={code} className="size-5 shrink-0" />
            <span className="min-w-0 truncate font-medium text-neutral-900 dark:text-neutral-100">
                {name}
            </span>
            <Badge variant="outline" className="shrink-0 font-mono text-xs">
                {code}
            </Badge>
            {isFullyTranslated && (
                <Tooltip>
                    <TooltipTrigger asChild>
                        <CheckCircle className="size-4 shrink-0 text-green-500" />
                    </TooltipTrigger>
                    <TooltipContent>Translation complete</TooltipContent>
                </Tooltip>
            )}
        </div>
    );
}

const STATUS_ICON_COLORS: Record<string, string> = {
    green: 'text-green-600 dark:text-green-400',
    blue: 'text-blue-600 dark:text-blue-400',
    amber: 'text-amber-600 dark:text-amber-400',
    red: 'text-red-600 dark:text-red-400',
    neutral: 'text-neutral-500 dark:text-neutral-400',
};

function StatusIconCell({
    value,
    column,
}: {
    value: unknown;
    column: TableColumnConfig;
}) {
    const strValue = String(value ?? '');
    const mapping: BadgeVariant = column.badgeMap?.[strValue] ?? {
        variant: 'outline',
    };
    const textColor =
        STATUS_ICON_COLORS[mapping.color ?? ''] ??
        STATUS_ICON_COLORS[mapping.variant] ??
        STATUS_ICON_COLORS.neutral;
    const icon = renderIcon(mapping.icon, undefined, `size-5 ${textColor}`);
    const label = mapping.label ?? strValue;

    return (
        <Tooltip>
            <TooltipTrigger asChild>
                <div className="flex items-center justify-center">
                    {icon ?? (
                        <span className={`text-xs font-medium ${textColor}`}>
                            {label}
                        </span>
                    )}
                </div>
            </TooltipTrigger>
            <TooltipContent>{label}</TooltipContent>
        </Tooltip>
    );
}

export function renderCellValue<T extends Record<string, unknown>>(
    row: T,
    column: TableColumnConfig,
): ReactNode {
    const key = column.accessorKey ?? column.id;
    const value = key.includes('.') ? getNestedValue(row, key) : row[key];

    switch (column.type) {
        case 'language':
            return <LanguageCell row={row} column={column} />;

        case 'progress':
            return <ProgressCell row={row} />;

        case 'status-icon':
            return <StatusIconCell value={value} column={column} />;

        case 'relative-time': {
            const label = typeof value === 'string' ? value : undefined;
            const dateVal = row[`${key}_at`];
            const rawDate = typeof dateVal === 'string' ? dateVal : undefined;
            if (!label) {
                return (
                    <span className="text-sm text-neutral-400 dark:text-neutral-500">
                        No activity
                    </span>
                );
            }
            return (
                <Tooltip>
                    <TooltipTrigger asChild>
                        <span className="cursor-default text-sm text-neutral-600 dark:text-neutral-400">
                            {label}
                        </span>
                    </TooltipTrigger>
                    <TooltipContent>
                        {rawDate
                            ? formatDate(rawDate, DEFAULT_DATETIME_FORMAT)
                            : label}
                    </TooltipContent>
                </Tooltip>
            );
        }

        default:
            break;
    }

    if (value === null || value === undefined) {
        return column.emptyValue ?? '—';
    }

    switch (column.type) {
        case 'mono':
            return (
                <span className="font-mono text-xs text-neutral-600 dark:text-neutral-300">
                    {column.prefix}
                    {column.truncate
                        ? `${String(value).slice(0, column.truncate)}...`
                        : String(value)}
                    {column.suffix}
                </span>
            );

        case 'badge':
            if (column.badgeMap) {
                const mapping: BadgeVariant = column.badgeMap[
                    String(value)
                ] ?? {
                    variant: 'outline',
                };
                return (
                    <Badge variant={mapping.variant}>
                        {renderIcon(mapping.icon, mapping.color, '')}
                        {mapping.label ?? String(value)}
                    </Badge>
                );
            }
            return <Badge variant="outline">{String(value)}</Badge>;

        case 'date':
            return formatDate(
                String(value),
                column.dateFormat ?? DEFAULT_DATE_FORMAT,
                column.emptyValue,
            );

        case 'datetime':
            return formatDate(
                String(value),
                column.dateFormat ?? DEFAULT_DATETIME_FORMAT,
                column.emptyValue,
            );

        case 'list':
            if (Array.isArray(value)) {
                return (
                    <span className="text-sm text-neutral-600 dark:text-neutral-400">
                        {value.join(', ')}
                    </span>
                );
            }
            return String(value);

        case 'boolean':
            return value ? 'Yes' : 'No';

        case 'text':
        default:
            return (
                <span className="font-medium text-neutral-900 dark:text-neutral-100">
                    {column.prefix}
                    {String(value)}
                    {column.suffix}
                </span>
            );
    }
}
