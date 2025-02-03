import React from "react";
import { Head, Link, router } from "@inertiajs/react";
import IconKey from "@/Components/Icons/IconKey";
import { PageProps, Phrase, PhrasePagination, Translation } from "@/types";
import DashboardLayout from '@/Layouts/DashboardLayout';
import IconEmptyTranslations from "@/Components/Icons/IconEmptyTranslations";
import {
    Button,
    Empty,
    Popover,
    Table,
    TableColumnsType,
    TableProps,
    Tooltip,
    Typography
} from "antd"
import { PencilIcon, PlusIcon, TrashIcon } from "@heroicons/react/24/outline";
import CountryFlag from "@/Components/Language/Flag";
import { ArrowRightIcon } from "@heroicons/react/16/solid";
import RenderPhraseWithParameters from "@/Components/Phrase/RenderPhraseWithParameters"

export interface DataType {
    key: React.Key;
    uuid: string;
    state: React.ReactNode;
    value: React.ReactNode;
    string: React.ReactNode;
    source: React.ReactNode;
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
    onSelect: (record, selected, selectedRows) => {
        console.log(record, selected, selectedRows);
    },
};

export interface Props {
    auth: PageProps['auth'];
    phrases: PhrasePagination;
    translation: Translation;
    files: any;
    filter: any;
}

const LanguageIndex: React.FC<Props> = ({ auth, phrases, translation, files, filter }) => {
    const handleTableChange: TableProps<DataType>['onChange'] = (pagination, filters, sorter, extra) => {
        router.get(route('ltu.phrases.index', { translation: translation.id }), {
            page: pagination.current,
            per_page: pagination.pageSize,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const columns: TableColumnsType<DataType> = [
        {
            title: (
                <div className="table-cell-title">
                    State
                </div>
            ),
            dataIndex: "state",
            width: "5%",
            align: "center",
            className: '!p-0',
        },
        {
            title: (
                <div className="table-cell-title">
                    Key
                </div>
            ),
            dataIndex: "value",
            width: "20%",
            className: "!p-0"
        },
        {
            title: (
                <div className="table-cell-title">
                    Source
                </div>
            ),
            dataIndex: "source",
            width: "30%",
            className: "!p-0",
        },
        {
            title: (
                <div className="table-cell-title">
                    String
                </div>
            ),
            dataIndex: "string",
            width: "30%",
            className: "!p-0"
        },
        {
            title: (
                <Tooltip title="Add new key">
                    <Button block type="default" title="Add new key" className="table-btn">
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
                    <Button block disabled type="default" title="Delete Selected Keys" className="table-btn">
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
        state: (
            <Link href={route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })} className="w-full h-full flex p-4">
                <div className="w-full flex items-center justify-center">
                    {phrase.value ? (
                        <div className="flex items-center justify-center size-5 bg-green-50 rounded-full">
                            <div className="size-2 bg-green-400 rounded-full"></div>
                        </div>
                    ) : (
                        <div className="flex items-center justify-center size-5 bg-red-50 rounded-full">
                            <div className="size-2 bg-red-400 rounded-full"></div>
                        </div>
                    )}
                </div>
            </Link>
        ),
        value: (
            <Link href={route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })} className="w-full h-full flex p-4">
                <div className="flex group items-center cursor-pointer gap-2 max-w-max truncate rounded-md border bg-white px-1.5 py-0.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600">
                    <IconKey className="size-4 text-gray-400 group-hover:text-blue-400" />
                    <span>{phrase.key}</span>
                </div>
            </Link>
        ),
        source: (
            <Link href={route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })} className="w-full h-full flex p-4 hover:text-gray-600">
                <RenderPhraseWithParameters
                    value={phrase.source?.value}
                    parameters={phrase.source?.parameters}
                />
            </Link>
        ),
        string: (
            <Link href={route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })} className="w-full h-full flex p-4">
                <span className="text-gray-500 line-clamp-1">
                    <Popover title={<span className="text-sm text-gray-600 font-medium">{phrase.value}</span>}>
                        {phrase.value}
                    </Popover>
                </span>
            </Link>
        ),
        translate: (
            <Link href={route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })}>
                <Tooltip title="Translate">
                    <Button block type="default" title="Translate" className="table-btn">
                        <PencilIcon className="size-5" />
                    </Button>
                </Tooltip>
            </Link>
        ),
        delete: (
            <Tooltip title="Delete">
                <Button block type="default" title="Delete" className="table-btn">
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
                    className="bg-white"
                    onChange={handleTableChange}
                    pagination={{
                        total: phrases.meta.total,
                        showSizeChanger: true,
                        position: ["bottomCenter"],
                        pageSize: phrases.meta.per_page,
                        current: phrases.meta.current_page,
                        defaultPageSize: phrases.meta.per_page,
                        showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`
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

export default LanguageIndex;