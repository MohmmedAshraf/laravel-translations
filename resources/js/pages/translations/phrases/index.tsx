import { Head, router, usePage } from '@inertiajs/react';
import { useCallback, useState } from 'react';
import {
    AdminDataTable,
    type PaginatedData,
    type RowAction,
    type TableConfig,
} from '@/components/data-table';
import { BatchTranslateDialog } from '@/components/translations/batch-translate-dialog';
import HighlightedText from '@/components/translations/highlighted-text';
import { TranslateAllDialog } from '@/components/translations/translate-all-dialog';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { DocumentText, Pen2, Stars } from '@/lib/icons';
import { index as languagesIndex } from '@/routes/ltu/languages';
import { index, edit } from '@/routes/ltu/phrases';
import type { BreadcrumbItem, Language } from '@/types';

interface PhraseRow {
    id: number;
    key: string;
    group_name: string | null;
    source_value: string | null;
    translation_value: string | null;
    status: string;
    priority: string;
    group: { id: number; name: string };
    translations: { id: number; value: string | null; status: string }[];
    [key: string]: unknown;
}

interface PageProps {
    data: PaginatedData<PhraseRow>;
    tableConfig: TableConfig;
    language: Language;
    sourceLanguage: Language | null;
    [key: string]: unknown;
}

const PLACEHOLDER_DATA: PhraseRow[] = [
    {
        id: 1,
        key: 'auth.login',
        group_name: 'auth',
        source_value: 'Log in to your account',
        translation_value: null,
        status: 'untranslated',
        priority: 'low',
        group: { id: 1, name: 'auth' },
        translations: [],
    },
    {
        id: 2,
        key: 'auth.register',
        group_name: 'auth',
        source_value: 'Create a new account',
        translation_value: 'Crear una cuenta nueva',
        status: 'translated',
        priority: 'low',
        group: { id: 1, name: 'auth' },
        translations: [],
    },
    {
        id: 3,
        key: 'dashboard.welcome',
        group_name: 'dashboard',
        source_value: 'Welcome back, :name!',
        translation_value: null,
        status: 'untranslated',
        priority: 'medium',
        group: { id: 2, name: 'dashboard' },
        translations: [],
    },
];

export default function Phrases() {
    const { data, tableConfig, language } = usePage<PageProps>().props;
    const [batchKeyIds, setBatchKeyIds] = useState<number[]>([]);
    const [batchDialogOpen, setBatchDialogOpen] = useState(false);
    const [translateAllOpen, setTranslateAllOpen] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Translations', href: languagesIndex().url },
        { title: language.name, href: index.url({ language: language.id }) },
    ];

    const rowActions: RowAction<PhraseRow>[] = [
        {
            label: 'Edit',
            icon: Pen2,
            onClick: (row) =>
                router.visit(
                    edit.url({
                        language: language.id,
                        translationKey: row.id,
                    }),
                ),
        },
    ];

    const handleBulkAction = useCallback(
        (name: string, ids: (string | number)[]) => {
            if (name === 'translate') {
                setBatchKeyIds(ids.map(Number));
                setBatchDialogOpen(true);
            }
        },
        [],
    );

    const handleBatchComplete = useCallback(() => {
        router.reload({ only: ['data', 'tableConfig'] });
    }, []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${language.name} Phrases`} />

            <div className="flex min-h-0 w-full flex-1 flex-col gap-4 p-4">
                <AdminDataTable
                    data={data}
                    tableConfig={tableConfig}
                    route={index.url({ language: language.id })}
                    getRowId={(row) => row.id}
                    onRowClick={(row) =>
                        router.visit(
                            edit.url({
                                language: language.id,
                                translationKey: row.id,
                            }),
                        )
                    }
                    rowActions={rowActions}
                    placeholderData={PLACEHOLDER_DATA}
                    emptyState={{
                        icon: DocumentText,
                        title: 'No phrases found',
                        description:
                            'No translation keys have been imported yet.',
                    }}
                    customCellRenderers={{
                        source_value: (row) =>
                            row.source_value ? (
                                <span className="text-sm">
                                    <HighlightedText text={row.source_value} />
                                </span>
                            ) : null,
                        translation_value: (row) =>
                            row.translation_value ? (
                                <span className="text-sm">
                                    <HighlightedText
                                        text={row.translation_value}
                                    />
                                </span>
                            ) : null,
                    }}
                    compactToolbar
                    columnStorageKey="ltu-phrases-columns"
                    exportFilename={`${language.code}-phrases`}
                    onBulkAction={handleBulkAction}
                    toolbarActions={
                        <Button
                            size="lg"
                            onClick={() => setTranslateAllOpen(true)}
                        >
                            <Stars className="size-4" />
                            Translate All Missing
                        </Button>
                    }
                />
            </div>

            <BatchTranslateDialog
                keyIds={batchKeyIds}
                targetLocale={language.code}
                open={batchDialogOpen}
                onOpenChange={setBatchDialogOpen}
                onComplete={handleBatchComplete}
            />

            <TranslateAllDialog
                locale={language.code}
                languageName={language.name}
                open={translateAllOpen}
                onOpenChange={setTranslateAllOpen}
                onComplete={handleBatchComplete}
            />
        </AppLayout>
    );
}
