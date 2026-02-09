import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import {
    AdminDataTable,
    type PaginatedData,
    type RowAction,
    type TableConfig,
} from '@/components/data-table';
import { EditContributorDialog } from '@/components/translations/contributors/edit-contributor-dialog';
import { InviteContributorDialog } from '@/components/translations/contributors/invite-contributor-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Flag } from '@/components/ui/flag';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import AppLayout from '@/layouts/app-layout';
import {
    AddCircle,
    CheckCircle,
    Forbidden,
    Pen2,
    UsersGroupRounded,
} from '@/lib/icons';
import { index, toggleActive } from '@/routes/ltu/contributors';
import { index as languagesIndex } from '@/routes/ltu/languages';
import type { BreadcrumbItem } from '@/types';

interface Language {
    id: number;
    code: string;
    name: string;
}

interface Role {
    value: string;
    label: string;
}

interface ContributorItem {
    id: string;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
    languages_list: string;
    languages: Language[];
    [key: string]: unknown;
}

interface PageProps {
    data: PaginatedData<ContributorItem>;
    tableConfig: TableConfig;
    languages: Language[];
    roles: Role[];
    [key: string]: unknown;
}

const PLACEHOLDER_DATA: ContributorItem[] = [
    {
        id: '1',
        name: 'Jane Smith',
        email: 'jane@example.com',
        role: 'admin',
        is_active: true,
        languages_list: 'ES, FR',
        languages: [],
    },
    {
        id: '2',
        name: 'Bob Johnson',
        email: 'bob@example.com',
        role: 'translator',
        is_active: true,
        languages_list: 'DE',
        languages: [],
    },
    {
        id: '3',
        name: 'Alice Martin',
        email: 'alice@example.com',
        role: 'reviewer',
        is_active: true,
        languages_list: 'All',
        languages: [],
    },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Translations', href: languagesIndex().url },
    { title: 'Contributors', href: index().url },
];

export default function ContributorsIndex() {
    const { data, tableConfig, languages, roles } = usePage<PageProps>().props;
    const [editContributor, setEditContributor] =
        useState<ContributorItem | null>(null);

    const rowActions: RowAction<ContributorItem>[] = [
        {
            label: 'Edit',
            icon: Pen2,
            onClick: (row) => setEditContributor(row),
        },
        {
            label: 'Activate',
            icon: CheckCircle,
            onClick: (row) =>
                router.post(
                    toggleActive.url(row.id),
                    {},
                    {
                        preserveScroll: true,
                    },
                ),
            hidden: (row) => row.is_active,
        },
        {
            label: 'Deactivate',
            icon: Forbidden,
            onClick: (row) =>
                router.post(
                    toggleActive.url(row.id),
                    {},
                    {
                        preserveScroll: true,
                    },
                ),
            variant: 'destructive',
            hidden: (row) => !row.is_active,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Contributors" />

            <div className="flex min-h-0 w-full flex-1 flex-col gap-4 p-4">
                <AdminDataTable
                    data={data}
                    tableConfig={tableConfig}
                    route={index().url}
                    getRowId={(row) => row.id}
                    rowActions={rowActions}
                    onRowClick={(row) => setEditContributor(row)}
                    customCellRenderers={{
                        languages_list: (row) => {
                            const langs = row.languages ?? [];
                            if (langs.length === 0) {
                                return (
                                    <span className="text-xs text-muted-foreground">
                                        All languages
                                    </span>
                                );
                            }
                            const visible = langs.slice(0, 4);
                            const remaining = langs.slice(4);
                            return (
                                <div className="flex items-center gap-1">
                                    {visible.map((lang) => (
                                        <Tooltip key={lang.id}>
                                            <TooltipTrigger asChild>
                                                <span className="inline-flex">
                                                    <Flag
                                                        code={lang.code}
                                                        className="size-5"
                                                    />
                                                </span>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                {lang.name}
                                            </TooltipContent>
                                        </Tooltip>
                                    ))}
                                    {remaining.length > 0 && (
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Badge
                                                    variant="secondary"
                                                    className="h-5 px-1.5 text-[10px]"
                                                >
                                                    +{remaining.length}
                                                </Badge>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                {remaining
                                                    .map((l) => l.name)
                                                    .join(', ')}
                                            </TooltipContent>
                                        </Tooltip>
                                    )}
                                </div>
                            );
                        },
                    }}
                    placeholderData={PLACEHOLDER_DATA}
                    emptyState={{
                        icon: UsersGroupRounded,
                        title: 'No contributors yet',
                        description:
                            'Invite contributors to start collaborating on translations.',
                    }}
                    toolbarActions={
                        <InviteContributorDialog
                            languages={languages}
                            roles={roles}
                            trigger={
                                <Button size="lg">
                                    <AddCircle className="size-4" />
                                    Invite Contributor
                                </Button>
                            }
                        />
                    }
                    compactToolbar
                    columnStorageKey="ltu-contributors-columns"
                />
            </div>

            {editContributor && (
                <EditContributorDialog
                    contributor={editContributor}
                    languages={languages}
                    roles={roles}
                    open={!!editContributor}
                    onOpenChange={(v) => !v && setEditContributor(null)}
                />
            )}
        </AppLayout>
    );
}
