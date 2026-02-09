import { Head, router, usePage } from '@inertiajs/react';
import { useCallback, useState } from 'react';
import { GroupCard } from '@/components/translations/group-card';
import { AddGroupDialog } from '@/components/translations/group-form-dialog';
import { Button } from '@/components/ui/button';
import { SearchBox } from '@/components/ui/search-box';
import AppLayout from '@/layouts/app-layout';
import { AddCircle, FolderWithFiles } from '@/lib/icons';
import { store, update, destroy, index } from '@/routes/ltu/groups';
import { index as languagesIndex } from '@/routes/ltu/languages';
import type { BreadcrumbItem, GroupWithKeys } from '@/types';

type Group = GroupWithKeys;

interface PageProps {
    groups: Group[];
    filter: { search?: string };
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Translations',
        href: languagesIndex().url,
    },
    {
        title: 'Groups',
        href: index().url,
    },
];

export default function Groups() {
    const { groups, filter } = usePage<PageProps>().props;
    const [search, setSearch] = useState(filter?.search ?? '');
    const [searching, setSearching] = useState(false);

    const handleSearchDebounced = useCallback((value: string) => {
        setSearching(true);
        router.get(index().url, value ? { filter: { search: value } } : {}, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => setSearching(false),
        });
    }, []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Groups" />

            <div className="flex min-h-0 w-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center gap-3">
                    <div className="min-w-0 flex-1 sm:max-w-xs">
                        <SearchBox
                            value={search}
                            loading={searching}
                            onChange={setSearch}
                            onChangeDebounced={handleSearchDebounced}
                            placeholder="Search groups..."
                        />
                    </div>
                    <div className="ml-auto">
                        <AddGroupDialog
                            submitUrl={store.url()}
                            trigger={
                                <Button size="lg">
                                    <AddCircle className="size-4" />
                                    Add Group
                                </Button>
                            }
                        />
                    </div>
                </div>

                {groups.length === 0 ? (
                    <div className="flex flex-1 flex-col items-center justify-center px-4 text-center">
                        <div className="flex max-w-sm flex-col items-center justify-center text-pretty">
                            <div className="mb-4 flex size-12 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                                <FolderWithFiles className="size-6 text-neutral-500" />
                            </div>
                            <span className="text-base font-medium text-neutral-900 dark:text-neutral-100">
                                {filter?.search
                                    ? 'No groups match your search'
                                    : 'No groups found'}
                            </span>
                            <p className="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                                {filter?.search
                                    ? 'Try a different search term.'
                                    : 'Groups are created automatically during import, or you can create one manually.'}
                            </p>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-2 gap-3 overflow-y-auto sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                        {groups.map((group) => (
                            <GroupCard
                                key={group.id}
                                group={group}
                                updateUrl={update.url({ group: group.id })}
                                deleteUrl={destroy.url({ group: group.id })}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
