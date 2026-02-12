import type { ReactNode } from 'react';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { useClipboard } from '@/hooks/use-clipboard';
import type { IconComponent } from '@/lib/icons';
import { CheckCircle, Copy, MagniferZoomIn } from '@/lib/icons';

export interface EmptyStateConfig {
    icon?:
        | IconComponent
        | ((props: React.SVGProps<SVGSVGElement>) => ReactNode);
    title: string;
    description?: string;
    command?: string;
    children?: ReactNode;
    action?: {
        label: string;
        icon?: IconComponent;
        onClick: () => void;
    };
}

export function DataTableEmptyState({
    icon: Icon,
    title,
    description,
    command,
    children,
    action,
}: EmptyStateConfig) {
    const ActionIcon = action?.icon;
    const [copiedText, copy] = useClipboard();
    const isCopied = copiedText === command;
    const CopyIcon = isCopied ? CheckCircle : Copy;

    return (
        <div className="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-t from-white from-50% to-white/60 px-4 text-center dark:from-neutral-900 dark:to-neutral-900/60">
            <div className="flex max-w-sm flex-col items-center justify-center text-center text-pretty">
                {Icon && (
                    <div className="mb-4 flex size-12 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                        <Icon className="size-6 text-neutral-500" />
                    </div>
                )}
                <span className="text-base font-medium text-neutral-900 dark:text-neutral-100">
                    {title}
                </span>
                {description && (
                    <p className="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                        {description}
                    </p>
                )}
                {command && (
                    <Tooltip open={isCopied || undefined}>
                        <TooltipTrigger asChild>
                            <button
                                type="button"
                                onClick={() => copy(command)}
                                className="mt-5 inline-flex items-center gap-2.5 rounded-full border border-neutral-200 bg-neutral-50 py-2 pr-3 pl-4 font-mono text-sm text-neutral-700 transition-colors hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700"
                            >
                                <span>{command}</span>
                                <CopyIcon
                                    className={`size-5 shrink-0 ${isCopied ? 'text-emerald-500' : 'text-neutral-400'}`}
                                />
                            </button>
                        </TooltipTrigger>
                        <TooltipContent>
                            {isCopied ? 'Copied!' : 'Copy command'}
                        </TooltipContent>
                    </Tooltip>
                )}
                {children}
            </div>

            {action && (
                <div className="mt-6">
                    <Button onClick={action.onClick} className="h-10">
                        {ActionIcon && <ActionIcon className="size-4" />}
                        {action.label}
                    </Button>
                </div>
            )}
        </div>
    );
}

interface NoResultsStateProps {
    title?: string;
    description?: ReactNode;
    onClearFilters?: () => void;
}

export function NoResultsState({
    title,
    description,
    onClearFilters,
}: NoResultsStateProps) {
    const displayTitle = title ?? 'No results found';
    const displayDescription =
        description ??
        'Try adjusting your search or filter to find what you are looking for.';
    return (
        <div className="flex flex-1 flex-col items-center justify-center px-4 py-16 text-center">
            <div className="mb-4 flex size-12 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                <MagniferZoomIn className="size-6 text-neutral-500" />
            </div>
            <span className="text-base font-medium text-neutral-900 dark:text-neutral-100">
                {displayTitle}
            </span>
            {displayDescription && (
                <p className="mt-2 max-w-sm text-sm text-neutral-500 dark:text-neutral-400">
                    {displayDescription}
                </p>
            )}
            {onClearFilters && (
                <Button
                    variant="outline"
                    size="sm"
                    onClick={onClearFilters}
                    className="mt-4"
                >
                    Clear filters
                    <kbd className="ml-2 rounded border border-neutral-200 bg-neutral-100 px-1.5 py-0.5 text-xs dark:border-neutral-700 dark:bg-neutral-800">
                        ESC
                    </kbd>
                </Button>
            )}
        </div>
    );
}
