import { router } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import { CloseCircle } from '@/lib/icons';
import { cn } from '@/lib/utils';
import type { BulkActionConfig } from './admin-data-table.types';
import { renderIcon } from './icon-map';

interface DataTableBulkActionsProps {
    selectedIds: (string | number)[];
    actions: BulkActionConfig[];
    onClearSelection: () => void;
    resourceName: string;
    onAction?: (name: string, ids: (string | number)[]) => void;
    selectAllPages?: boolean;
    totalCount?: number;
}

export function DataTableBulkActions({
    selectedIds,
    actions,
    onClearSelection,
    resourceName,
    onAction,
    selectAllPages = false,
    totalCount = 0,
}: DataTableBulkActionsProps) {
    const [confirmAction, setConfirmAction] = useState<BulkActionConfig | null>(
        null,
    );
    const [isSubmitting, setIsSubmitting] = useState(false);

    const isVisible =
        (selectedIds.length > 0 || selectAllPages) && actions.length > 0;
    const displayCount = selectAllPages ? totalCount : selectedIds.length;

    const handleAction = (action: BulkActionConfig) => {
        if (action.confirm) {
            setConfirmAction(action);
        } else {
            executeAction(action);
        }
    };

    const buildActionUrl = (url: string): string => {
        if (!selectAllPages) return url;
        const queryString = window.location.search;
        return queryString ? `${url}${queryString}` : url;
    };

    const buildPayload = () => {
        if (selectAllPages) {
            return { select_all: true };
        }
        return { ids: selectedIds };
    };

    const executeAction = (action: BulkActionConfig) => {
        if (!action.url && onAction) {
            onAction(action.name, selectedIds);
            onClearSelection();
            setConfirmAction(null);
            return;
        }

        if (!action.url) return;

        if (action.download) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = buildActionUrl(action.url);
            form.style.display = 'none';

            const csrfToken = document.querySelector<HTMLMetaElement>(
                'meta[name="csrf-token"]',
            )?.content;
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            if (selectAllPages) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'select_all';
                input.value = '1';
                form.appendChild(input);
            } else {
                selectedIds.forEach((id) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = String(id);
                    form.appendChild(input);
                });
            }

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            toast.success(`Exporting ${displayCount} ${resourceName}...`);
            onClearSelection();
            setConfirmAction(null);
            return;
        }

        setIsSubmitting(true);

        router.post(buildActionUrl(action.url), buildPayload(), {
            preserveScroll: true,
            onSuccess: () => {
                onClearSelection();
                setConfirmAction(null);
            },
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    return (
        <>
            <div
                className={cn(
                    'fixed inset-x-0 bottom-6 z-50 flex justify-center transition-all duration-300 ease-out',
                    isVisible
                        ? 'translate-y-0 opacity-100'
                        : 'pointer-events-none translate-y-4 opacity-0',
                )}
            >
                <div className="flex items-center gap-1.5 rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 shadow-2xl dark:border-neutral-600 dark:bg-neutral-800">
                    <div className="flex items-center gap-2 pr-1.5">
                        <span className="flex h-6 min-w-6 items-center justify-center rounded-md bg-white px-1.5 text-xs font-semibold text-neutral-900 tabular-nums dark:bg-neutral-100">
                            {displayCount.toLocaleString()}
                        </span>
                        <span className="text-sm font-medium text-neutral-300">
                            selected
                        </span>
                    </div>

                    <div className="mx-1 h-5 w-px bg-neutral-700 dark:bg-neutral-600" />

                    {actions.map((action) => (
                        <button
                            key={action.name}
                            type="button"
                            onClick={() => handleAction(action)}
                            disabled={isSubmitting}
                            className={cn(
                                'flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium transition-colors disabled:opacity-50',
                                action.variant === 'destructive'
                                    ? 'text-red-400 hover:bg-red-500/15 hover:text-red-300'
                                    : 'text-neutral-300 hover:bg-neutral-700 hover:text-white dark:hover:bg-neutral-700',
                            )}
                        >
                            {action.icon &&
                                renderIcon(action.icon, undefined, 'size-4')}
                            {action.label}
                        </button>
                    ))}

                    <div className="mx-1 h-5 w-px bg-neutral-700 dark:bg-neutral-600" />

                    <button
                        type="button"
                        onClick={onClearSelection}
                        className="flex items-center justify-center rounded-lg p-1.5 text-neutral-500 transition-colors hover:bg-neutral-700 hover:text-neutral-300"
                    >
                        <CloseCircle className="size-4" />
                    </button>
                </div>
            </div>

            <Dialog
                open={!!confirmAction}
                onOpenChange={(open: boolean) => {
                    if (!open && !isSubmitting) {
                        setConfirmAction(null);
                    }
                }}
            >
                {confirmAction && (
                    <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle>
                                {confirmAction.confirmTitle}
                            </DialogTitle>
                            <DialogDescription className="sr-only">
                                {confirmAction.confirm}
                            </DialogDescription>
                        </DialogHeader>

                        <div className="space-y-4">
                            <p className="text-sm text-neutral-600 dark:text-neutral-400">
                                {confirmAction.confirm}
                            </p>

                            <p className="text-sm text-neutral-600 dark:text-neutral-400">
                                This will affect{' '}
                                <span className="font-medium text-neutral-900 dark:text-neutral-100">
                                    {displayCount} {resourceName}
                                </span>
                                .
                            </p>

                            <div className="grid grid-cols-5 gap-3">
                                <Button
                                    type="button"
                                    variant="outline"
                                    className="col-span-2"
                                    onClick={() => setConfirmAction(null)}
                                    disabled={isSubmitting}
                                >
                                    Cancel
                                </Button>
                                <Button
                                    variant={
                                        confirmAction.variant === 'destructive'
                                            ? 'destructive'
                                            : 'default'
                                    }
                                    className="col-span-3"
                                    onClick={() => executeAction(confirmAction)}
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting ? (
                                        <>
                                            <Spinner className="size-4" />
                                            Processing...
                                        </>
                                    ) : (
                                        'Confirm'
                                    )}
                                </Button>
                            </div>
                        </div>
                    </DialogContent>
                )}
            </Dialog>
        </>
    );
}
