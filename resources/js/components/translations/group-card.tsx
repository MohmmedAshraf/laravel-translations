import { router } from '@inertiajs/react';
import { useState } from 'react';
import { EditGroupDialog } from '@/components/translations/group-form-dialog';
import { Button } from '@/components/ui/button';
import { ConfirmDialog } from '@/components/ui/confirm-dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { FolderWithFiles, MenuDots, Pen2, TrashBin2 } from '@/lib/icons';
import type { GroupWithKeys } from '@/types';

interface GroupCardProps {
    group: GroupWithKeys;
    updateUrl: string;
    deleteUrl: string;
}

export function displayGroupName(group: {
    name: string;
    namespace?: string | null;
}): string {
    if (group.name === '_json') {
        return 'JSON (root)';
    }

    if (group.namespace) {
        return `${group.namespace}::${group.name}`;
    }

    return group.name;
}

export function GroupCard({ group, updateUrl, deleteUrl }: GroupCardProps) {
    const [editOpen, setEditOpen] = useState(false);
    const [deleteOpen, setDeleteOpen] = useState(false);
    const keyCount = group.translation_keys_count;

    return (
        <>
            <div className="group relative overflow-hidden rounded-2xl border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
                <div className="absolute top-2 right-2 z-10 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100">
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button
                                variant="ghost"
                                size="icon"
                                className="size-7"
                            >
                                <MenuDots className="size-4" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuItem
                                onSelect={() => setEditOpen(true)}
                            >
                                <Pen2 className="size-4" />
                                Edit
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                className="text-red-600 focus:text-red-600 dark:text-red-400"
                                onSelect={() => setDeleteOpen(true)}
                            >
                                <TrashBin2 className="size-4" />
                                Delete
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <div className="flex items-center justify-center bg-neutral-50 py-8 dark:bg-neutral-800/50">
                    <FolderWithFiles className="size-12 text-neutral-400 dark:text-neutral-500" />
                </div>

                <div className="px-3.5 pt-3 pb-3.5">
                    <p className="truncate text-sm font-medium text-neutral-900 dark:text-neutral-100">
                        {displayGroupName(group)}
                    </p>
                    <p className="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400">
                        {keyCount} {keyCount === 1 ? 'key' : 'keys'}
                        {group.namespace && ' · Vendor'}
                    </p>
                </div>
            </div>

            <EditGroupDialog
                initialValues={{
                    name: group.name,
                    namespace: group.namespace || '',
                }}
                submitUrl={updateUrl}
                open={editOpen}
                onOpenChange={setEditOpen}
            />

            <ConfirmDialog
                open={deleteOpen}
                onOpenChange={setDeleteOpen}
                onConfirm={() => router.delete(deleteUrl)}
                title="Delete Group"
                description={`Delete "${displayGroupName(group)}" and all ${keyCount} translation ${keyCount === 1 ? 'key' : 'keys'} with their translations? This action cannot be undone.`}
                confirmLabel="Delete"
            />
        </>
    );
}
