import { Head, usePage, router } from '@inertiajs/react';
import * as React from 'react';
import {
    AdminDataTable,
    type PaginatedData,
    type RowAction,
    type TableConfig,
} from '@/components/data-table';
import {
    AddLanguageDialog,
    type AvailableLanguage,
} from '@/components/translations/add-language-dialog';
import { RemoveLanguageDialog } from '@/components/translations/remove-language-dialog';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/app-layout';
import {
    AddCircle,
    DownloadMinimalistic,
    Languages as LanguagesIcon,
    TrashBin2,
    Upload,
} from '@/lib/icons';
import { importMethod, exportMethod } from '@/routes/ltu';
import { index } from '@/routes/ltu/languages';
import { index as phrasesIndex } from '@/routes/ltu/phrases';
import { index as sourceIndex } from '@/routes/ltu/source';
import type { BreadcrumbItem, LanguageWithStats } from '@/types';

type Language = LanguageWithStats;

interface PageProps {
    data: PaginatedData<Language>;
    tableConfig: TableConfig;
    availableLanguages: AvailableLanguage[];
    totalKeys: number;
    [key: string]: unknown;
}

const PLACEHOLDER_DATA: Language[] = [
    {
        id: 1,
        code: 'en',
        name: 'English',
        native_name: 'English',
        rtl: false,
        is_source: true,
        active: true,
        progress: 100,
        translated_count: 128,
        untranslated_count: 0,
        needs_review_count: 0,
        total_keys: 128,
        last_activity: null,
        last_activity_at: null,
    },
    {
        id: 2,
        code: 'fr',
        name: 'French',
        native_name: 'Français',
        rtl: false,
        is_source: false,
        active: true,
        progress: 85,
        translated_count: 109,
        untranslated_count: 12,
        needs_review_count: 7,
        total_keys: 128,
        last_activity: null,
        last_activity_at: null,
    },
    {
        id: 3,
        code: 'es',
        name: 'Spanish',
        native_name: 'Español',
        rtl: false,
        is_source: false,
        active: true,
        progress: 62,
        translated_count: 79,
        untranslated_count: 38,
        needs_review_count: 11,
        total_keys: 128,
        last_activity: null,
        last_activity_at: null,
    },
    {
        id: 4,
        code: 'de',
        name: 'German',
        native_name: 'Deutsch',
        rtl: false,
        is_source: false,
        active: true,
        progress: 41,
        translated_count: 52,
        untranslated_count: 64,
        needs_review_count: 12,
        total_keys: 128,
        last_activity: null,
        last_activity_at: null,
    },
    {
        id: 5,
        code: 'ar',
        name: 'Arabic',
        native_name: 'العربية',
        rtl: true,
        is_source: false,
        active: true,
        progress: 15,
        translated_count: 19,
        untranslated_count: 97,
        needs_review_count: 12,
        total_keys: 128,
        last_activity: null,
        last_activity_at: null,
    },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Translations',
        href: index().url,
    },
];

function languageHref(language: Language): string {
    if (language.is_source) {
        return sourceIndex.url();
    }
    return phrasesIndex.url({ language: language.id });
}

export default function Languages() {
    const { data, tableConfig, availableLanguages, totalKeys } =
        usePage<PageProps>().props;

    const [removeLanguage, setRemoveLanguage] = React.useState<Language | null>(
        null,
    );
    const [importing, setImporting] = React.useState(false);
    const [exporting, setExporting] = React.useState(false);
    const handleImport = React.useCallback(() => {
        setImporting(true);
        router.post(
            importMethod.url(),
            {},
            {
                onFinish: () => setImporting(false),
            },
        );
    }, []);
    const handleExport = React.useCallback(() => {
        setExporting(true);
        router.post(
            exportMethod.url(),
            {},
            {
                onFinish: () => setExporting(false),
            },
        );
    }, []);

    const rowActions: RowAction<Language>[] = [
        {
            label: 'Remove',
            icon: TrashBin2,
            variant: 'destructive',
            onClick: (row) => setRemoveLanguage(row),
            disabled: (row) => row.is_source,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Languages" />

            <div className="flex min-h-0 w-full flex-1 flex-col gap-4 p-4">
                <AdminDataTable
                    data={data}
                    tableConfig={tableConfig}
                    route={index().url}
                    getRowId={(row) => row.id}
                    onRowClick={(row) => router.visit(languageHref(row))}
                    rowActions={rowActions}
                    placeholderData={PLACEHOLDER_DATA}
                    emptyState={{
                        icon: LanguagesIcon,
                        title: 'No languages found',
                        description: 'Run the import command to get started.',
                        command: 'php artisan translations:import',
                        children: (
                            <Button
                                size="lg"
                                className="mt-4"
                                onClick={handleImport}
                                disabled={importing}
                            >
                                {importing ? (
                                    <Spinner />
                                ) : (
                                    <DownloadMinimalistic className="size-4" />
                                )}
                                Import Translations
                            </Button>
                        ),
                    }}
                    toolbarActions={
                        <>
                            {data.data.length > 0 && (
                                <Button
                                    size="lg"
                                    variant="outline"
                                    onClick={handleExport}
                                    disabled={exporting}
                                >
                                    {exporting ? (
                                        <Spinner />
                                    ) : (
                                        <Upload className="size-4" />
                                    )}
                                    Export
                                </Button>
                            )}
                            {data.data.some((l) => l.is_source) ? (
                                <AddLanguageDialog
                                    availableLanguages={availableLanguages}
                                    totalKeys={totalKeys}
                                    trigger={
                                        <Button size="lg">
                                            <AddCircle className="size-4" />
                                            Add Language
                                        </Button>
                                    }
                                />
                            ) : (
                                <Button
                                    size="lg"
                                    disabled
                                    title="Import translations first to add languages"
                                >
                                    <AddCircle className="size-4" />
                                    Add Language
                                </Button>
                            )}
                        </>
                    }
                    compactToolbar
                    columnStorageKey="ltu-languages-columns"
                    isRowSelectable={(row) => !row.is_source}
                />
            </div>

            {removeLanguage && (
                <RemoveLanguageDialog
                    language={removeLanguage}
                    open={!!removeLanguage}
                    onOpenChange={(open) => {
                        if (!open) setRemoveLanguage(null);
                    }}
                />
            )}
        </AppLayout>
    );
}
