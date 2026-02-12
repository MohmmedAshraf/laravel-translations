import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import {
    AdminDataTable,
    type PaginatedData,
    type RowAction,
    type TableConfig,
} from '@/components/data-table';
import { AddKeyDialog } from '@/components/translations/add-key-dialog';
import HighlightedText from '@/components/translations/highlighted-text';
import { Button } from '@/components/ui/button';
import { ConfirmDialog } from '@/components/ui/confirm-dialog';
import AppLayout from '@/layouts/app-layout';
import { AddCircle, DocumentText, Pen2, TrashBin2 } from '@/lib/icons';
import { index as languagesIndex } from '@/routes/ltu/languages';
import { index, show, destroy } from '@/routes/ltu/source';
import type { BreadcrumbItem, Group } from '@/types';

interface SourcePhraseRow {
    id: number;
    key: string;
    group_name: string | null;
    source_value: string | null;
    priority: string;
    group: { id: number; name: string };
    translations: { id: number; value: string | null; status: string }[];
    [key: string]: unknown;
}

interface PageProps {
    data: PaginatedData<SourcePhraseRow>;
    tableConfig: TableConfig;
    groups: Group[];
    sourceLanguage: { id: number; code: string; name: string };
    [key: string]: unknown;
}

const PLACEHOLDER_DATA: SourcePhraseRow[] = [
    {
        id: 1,
        key: 'auth.login',
        group_name: 'auth',
        source_value: 'Log in to your account',
        priority: 'low',
        group: { id: 1, name: 'auth' },
        translations: [],
    },
    {
        id: 2,
        key: 'auth.register',
        group_name: 'auth',
        source_value: 'Create a new account',
        priority: 'low',
        group: { id: 1, name: 'auth' },
        translations: [],
    },
    {
        id: 3,
        key: 'dashboard.welcome',
        group_name: 'dashboard',
        source_value: 'Welcome back, :name!',
        priority: 'medium',
        group: { id: 2, name: 'dashboard' },
        translations: [],
    },
];

export default function SourceLanguageIndex() {
    const { data, tableConfig, groups, sourceLanguage } =
        usePage<PageProps>().props;
    const [deleteTarget, setDeleteTarget] = useState<SourcePhraseRow | null>(
        null,
    );

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Translations', href: languagesIndex().url },
        {
            title: `Source Language (${sourceLanguage.name})`,
            href: index().url,
        },
    ];

    const rowActions: RowAction<SourcePhraseRow>[] = [
        {
            label: 'Edit',
            icon: Pen2,
            onClick: (row) =>
                router.visit(show.url({ translationKey: row.id })),
        },
        {
            label: 'Delete',
            icon: TrashBin2,
            variant: 'destructive',
            onClick: (row, e) => {
                e?.stopPropagation();
                setDeleteTarget(row);
            },
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Source Language (${sourceLanguage.name})`} />

            <div className="flex min-h-0 w-full flex-1 flex-col gap-4 p-4">
                <AdminDataTable
                    data={data}
                    tableConfig={tableConfig}
                    route={index().url}
                    getRowId={(row) => row.id}
                    onRowClick={(row) =>
                        router.visit(show.url({ translationKey: row.id }))
                    }
                    rowActions={rowActions}
                    placeholderData={PLACEHOLDER_DATA}
                    emptyState={{
                        icon: DocumentText,
                        title: 'No translation keys',
                        description:
                            'Run the import command or add keys manually.',
                    }}
                    toolbarActions={
                        <AddKeyDialog
                            groups={groups}
                            trigger={
                                <Button size="lg">
                                    <AddCircle className="size-4" />
                                    Add Key
                                </Button>
                            }
                        />
                    }
                    customCellRenderers={{
                        source_value: (row) =>
                            row.source_value ? (
                                <span className="text-sm">
                                    <HighlightedText text={row.source_value} />
                                </span>
                            ) : null,
                    }}
                    compactToolbar
                    columnStorageKey="ltu-source-columns"
                    exportFilename="source-phrases"
                />
            </div>

            <ConfirmDialog
                open={deleteTarget !== null}
                onOpenChange={(open) => {
                    if (!open) setDeleteTarget(null);
                }}
                onConfirm={() => {
                    if (deleteTarget) {
                        router.delete(
                            destroy.url({ translationKey: deleteTarget.id }),
                        );
                    }
                }}
                title="Delete Translation Key"
                description={`Are you sure you want to delete "${deleteTarget?.key}"? This action cannot be undone.`}
                confirmLabel="Delete"
            />
        </AppLayout>
    );
}
