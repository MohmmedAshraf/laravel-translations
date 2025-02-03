import React from "react";
import { Head, Link, router } from "@inertiajs/react";
import IconKey from "@/Components/Icons/IconKey";
import { PageProps, Phrase, PhrasePagination, Translation } from "@/types";
import DashboardLayout from '@/Layouts/DashboardLayout';
import IconEmptyTranslations from "@/Components/Icons/IconEmptyTranslations";
import { Button, Empty, Table, TableColumnsType, TableProps, Tooltip, Typography } from "antd";
import { PencilIcon, PlusIcon, TrashIcon } from "@heroicons/react/24/outline";
import CountryFlag from "@/Components/Language/Flag";
import { ArrowRightIcon } from "@heroicons/react/16/solid";
import RenderPhraseWithParameters
    from "@/Components/Phrase/RenderPhraseWithParameters"

interface DataType {
    key: React.Key;
    uuid: string;
    value: React.ReactNode;
    string: React.ReactNode;
    translate: React.ReactNode;
    delete: React.ReactNode;
}

const rowSelection: TableProps<DataType>['rowSelection'] = {
    onChange: (selectedRowKeys: React.Key[], selectedRows: DataType[]) => {
        console.log(`selectedRowKeys: ${selectedRowKeys}`, 'selectedRows: ', selectedRows);
    },
    getCheckboxProps: (record: DataType) => ({
        key: record.key,
        disabled: false,
    }),
};

export interface Props {
    auth: PageProps['auth'];
    phrases: PhrasePagination;
    translation: Translation;
    files: any;
    filter: any;
}

const SourceTranslationsIndex: React.FC<Props> = ({ auth, phrases, translation, files, filter }) => {
    const handleTableChange: TableProps<DataType>['onChange'] = (pagination, filters, sorter, extra) => {
        router.get(route('ltu.source_translation'), {
            page: pagination.current,
            per_page: pagination.pageSize,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const columns: TableColumnsType<DataType> = [
        {
            title: 'Key',
            dataIndex: 'value',
            width: '35%',
        },
        {
            title: 'String',
            dataIndex: 'string',
            width: '45%',
        },
        {
            title: (
                <Tooltip title="Add new key">
                    <Button block type="default" title="Add new key" className="border-none h-14 bg-transparent rounded-none">
                        <PlusIcon className="size-5" />
                    </Button>
                </Tooltip>
            ),
            className: '!p-0',
            dataIndex: 'translate',
            width: '5%',
        },
        {
            title: (
                <Tooltip title="Delete Selected Keys">
                    <Button block disabled type="default" title="Delete Selected Keys" className="border-none h-14 bg-transparent rounded-none">
                        <TrashIcon className="size-5" />
                    </Button>
                </Tooltip>
            ),
            className: '!p-0',
            dataIndex: 'delete',
            width: '5%',
        },
    ];

    const data: DataType[] = phrases.data.map((phrase: Phrase) => ({
        key: phrase.uuid,
        uuid: phrase.uuid,
        value: (
            <div className="flex group items-center cursor-pointer gap-2 max-w-max truncate rounded-md border bg-white px-1.5 py-0.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600">
                <IconKey className="size-4 text-gray-400 group-hover:text-blue-400" />
                <span>{phrase.key}</span>
            </div>
        ),
        string: (
            <RenderPhraseWithParameters
                value={phrase.value}
                parameters={phrase.parameters}
            />
        ),
        translate: (
            <Link href={route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })}>
                <Tooltip title="Translate">
                    <Button block type="default" title="Translate" className="border-none h-14 text-gray-400 bg-transparent rounded-none">
                        <PencilIcon className="size-5" />
                    </Button>
                </Tooltip>
            </Link>
        ),
        delete: (
            <Tooltip title="Delete">
                <Button block type="default" title="Delete" className="border-none h-14 text-gray-400 bg-transparent rounded-none">
                    <TrashIcon className="size-5" />
                </Button>
            </Tooltip>
        ),
    }));

    return (
        <DashboardLayout
            user={auth.user}
            header={
                <>
                    <div className="flex w-full items-center gap-3 py-2">
                        <Link href="#" className="flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100">
                            <div className="h-5 shrink-0">
                                <CountryFlag countryCode={translation.language.code} />
                            </div>
                            <div className="flex items-center space-x-2">
                                <div className="text-sm font-semibold text-gray-600">
                                    {translation.language.name}
                                </div>
                            </div>
                        </Link>
                        <div className="rounded-md border bg-white px-1.5 py-0.5 text-sm text-gray-500">
                            {translation.language.code}
                        </div>
                    </div>

                    <Link href="#" className="flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200">
                        <ArrowRightIcon className="size-6 text-gray-400" />
                    </Link>
                </>
            }
        >
            <Head title={translation.language.name} />

            <div className="rounded-lg shadow overflow-hidden mx-auto">
                <Table
                    bordered
                    columns={columns}
                    dataSource={data}
                    onChange={handleTableChange}
                    pagination={{
                        total: phrases.meta.total,
                        showSizeChanger: true,
                        position: ['bottomCenter'],
                        pageSize: phrases.meta.per_page,
                        current: phrases.meta.current_page,
                        defaultPageSize: phrases.meta.per_page,
                        showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`,
                    }}
                    rowSelection={{
                        type: 'checkbox',
                        columnWidth: '5%',
                        ...rowSelection,
                    }}
                    locale={{
                        emptyText: (
                            <Empty
                                className="py-24"
                                image={<IconEmptyTranslations className="size-24 mb-4 fill-gray-300" />}
                                description={
                                    <Typography.Text>
                                        There are no keys to translate your project to. <br /> Add the first one and start translating.
                                    </Typography.Text>
                                }
                            >
                                <Button
                                    size="large"
                                    type="primary"
                                    className="uppercase text-sm font-medium tracking-tight"
                                    icon={<PlusIcon className="size-5" />}
                                >
                                    Add your first key
                                </Button>
                            </Empty>
                        )
                    }}>
                </Table>
            </div>
        </DashboardLayout>
    );
}

export default SourceTranslationsIndex;