import React, { useState, useEffect, useCallback } from "react";
import { Head, Link, router } from "@inertiajs/react";
import IconKey from "@/Components/Icons/IconKey";
import { PageProps, Phrase, PhrasePagination, Translation, TranslationFile } from "@/types";
import DashboardLayout from '@/Layouts/DashboardLayout';
import IconEmptyTranslations from "@/Components/Icons/IconEmptyTranslations";
import {
    Button,
    Empty,
    Input,
    Popover,
    Select,
    Space,
    Spin,
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
import { CheckIcon, GlobeAltIcon } from "@heroicons/react/16/solid";

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

export interface PhraseFilter {
    keyword?: string;
    status?: "translated" | "untranslated";
    translationFile?: number;
}

export interface Props {
    auth: PageProps['auth'];
    phrases: PhrasePagination;
    translation: Translation;
    files: TranslationFile[];
    filter: PhraseFilter;
}

const LanguageIndex: React.FC<Props> = ({ auth, phrases, translation, files, filter }) => {
    const [searchField, setSearchField] = useState<string>(filter.keyword || "");
    const [phraseStatus, setPhraseStatus] = useState<string | undefined>(filter.status);
    const [phraseTranslationFile, setPhraseTranslationFile] = useState<number | undefined>(filter.translationFile);
    const [loading, setLoading] = useState(false);

    const debounceTimer = React.useRef<NodeJS.Timeout | null>(null);

    const translationFiles = files.map((file) => ({
        value: file.id,
        label: `${file.name}.${file.extension}`,
    }));

    const handleFilterChange = useCallback(() => {
        setLoading(true);
        
        const newFilter: any = {};
        if (searchField) newFilter.keyword = searchField;
        if (phraseStatus) newFilter.status = phraseStatus;
        if (phraseTranslationFile) newFilter.translationFile = phraseTranslationFile;

        router.get(
            route("ltu.phrases.index", [translation.id]),
            {
                filter: Object.keys(newFilter).length > 0 ? newFilter : undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                only: ["phrases"],
                onFinish: () => setLoading(false),
            }
        );
    }, [searchField, phraseStatus, phraseTranslationFile, translation.id]);

    useEffect(() => {
        if (debounceTimer.current) {
            clearTimeout(debounceTimer.current);
        }

        debounceTimer.current = setTimeout(() => {
            handleFilterChange();
        }, 300);

        return () => {
            if (debounceTimer.current) {
                clearTimeout(debounceTimer.current);
            }
        };
    }, [searchField, phraseStatus, phraseTranslationFile, handleFilterChange]);
    const handleTableChange: TableProps<DataType>['onChange'] = (pagination) => {
        const newFilter: any = {};
        if (searchField) newFilter.keyword = searchField;
        if (phraseStatus) newFilter.status = phraseStatus;
        if (phraseTranslationFile) newFilter.translationFile = phraseTranslationFile;

        router.get(
            route("ltu.phrases.index", [translation.id]),
            {
                page: pagination.current,
                per_page: pagination.pageSize,
                filter: Object.keys(newFilter).length > 0 ? newFilter : undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                only: ["phrases"],
            }
        );
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
            <div className="flex items-center justify-center w-full">
                {phrase.value ? (
                    <Tooltip title="Translated">
                        <CheckIcon className="size-5 text-green-600" />
                    </Tooltip>
                ) : (
                    <Tooltip title="Untranslated">
                        <GlobeAltIcon className="size-5 text-gray-500" />
                    </Tooltip>
                )}
            </div>
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
            <Head title={`${translation.language.name} - Phrases`} />

            {/* Filters */}
            <div className="mb-4 rounded-lg bg-white p-4 shadow">
                <Space wrap style={{ width: "100%" }}>
                    <Input
                        placeholder="Search by key or value"
                        value={searchField}
                        onChange={(e) => setSearchField(e.target.value)}
                        style={{ width: 250 }}
                        allowClear
                    />
                    
                    <Select
                        placeholder="Filter by status"
                        style={{ width: 150 }}
                        value={phraseStatus || undefined}
                        onChange={setPhraseStatus}
                        allowClear
                        options={[
                            { label: "Translated", value: "translated" },
                            { label: "Untranslated", value: "untranslated" },
                        ]}
                    />

                    <Select
                        placeholder="Filter by file"
                        style={{ width: 200 }}
                        value={phraseTranslationFile || undefined}
                        onChange={setPhraseTranslationFile}
                        allowClear
                        options={translationFiles}
                    />
                </Space>
            </div>

            {/* Table */}
            <Spin spinning={loading}>
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
            </Spin>
        </DashboardLayout>
    );
}

export default LanguageIndex;
