import React, { useState } from "react";
import { Head, Link, router } from "@inertiajs/react"
import IconSmile from "@/Components/Icons/IconSmile";
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Language, PageProps, Translation } from '@/types';
import LanguageTag from "@/Components/Language/LanguageTag";
import IconTranslate from "@/Components/Icons/IconTranslate";
import AddTranslations from "@/Pages/Modals/AddTranslations";
import PublishTranslations from "@/Pages/Modals/PublishTranslations";
import IconEmptyTranslations from "@/Components/Icons/IconEmptyTranslations";
import {
    Button,
    Empty, message,
    Popconfirm,
    Progress,
    Table,
    TableColumnsType,
    TableProps,
    Tooltip,
    Typography
} from "antd"
import { Cog6ToothIcon, PlusIcon, TrashIcon } from "@heroicons/react/24/outline";
import { QuestionCircleOutlined } from "@ant-design/icons";

interface DataType {
    key: React.Key;
    source: boolean;
    name: React.ReactNode;
    progress: React.ReactNode;
    add: React.ReactNode;
    delete: React.ReactNode;
}

const TranslationsIndex: React.FC<PageProps<{ translations: Translation[], languages: Language[] }>> = ({ auth, translations, languages }) => {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isPublishModalOpen, setIsPublishModalOpen] = useState(false);
    const [confirmLoading, setConfirmLoading] = useState(false);
    const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);

    const canPublish = translations.some(t => !t.source);
    const isProductionEnv = process.env.NODE_ENV === 'production';

    const rowSelection: TableProps<DataType>['rowSelection'] = {
        onChange: (selectedRowKeys: React.Key[], selectedRows: DataType[]) => {
            setSelectedRowKeys(selectedRowKeys);
        },
        getCheckboxProps: (record: DataType) => ({
            disabled: record.source,
            key: record.key,
        }),
    };

    const confirmed = async (translationIdOrIds: number | number[]): Promise<void> => {
        try {
            setConfirmLoading(true);

            let response;

            if (Array.isArray(translationIdOrIds)) {
                response = await window.axios.post<{ message: string }>(
                    route('ltu.translation.delete_multiple'),
                    {
                        selected_ids: translationIdOrIds,
                    }
                );
            } else {
                response = await window.axios.delete<{ message: string }>(
                    route('ltu.translation.delete', translationIdOrIds)
                );
            }

            if (response.status === 200) {
                message.success(response.data.message);
            } else {
                message.error('Failed to delete translation(s)');
            }
        } catch (error) {
            message.error('Failed to delete translation(s)');
        } finally {
            setConfirmLoading(false);
            router.visit(route('ltu.translation.index'), {
                replace: true,
                only: ['translations'],
            });
        }
    };

    const columns: TableColumnsType<DataType> = [
        {
            title: (
                <div className="table-cell-title">
                    Language name
                </div>
            ),
            dataIndex: 'name',
            width: '35%',
            className: '!p-0',
        },
        {
            title: (
                <div className="table-cell-title">
                    Translation progress
                </div>
            ),
            dataIndex: 'progress',
            width: '45%',
            className: '!p-0',
        },
        {
            title: (
                <Button
                    block
                    type="default"
                    onClick={() => setIsModalOpen(true)}
                    className="table-btn"
                >
                    <PlusIcon className="size-5" />
                </Button>
            ),
            className: '!p-0',
            dataIndex: 'add',
            width: '5%',
        },
        {
            title: (
                <Popconfirm
                    okText="Yes"
                    cancelText="No"
                    placement="left"
                    title="Delete Selected Translations"
                    onConfirm={() => confirmed(selectedRowKeys.map(Number))}
                    okButtonProps={{ loading: confirmLoading }}
                    cancelButtonProps={{ disabled: confirmLoading }}
                    description="Are you sure to delete selected translations?"
                    icon={<QuestionCircleOutlined style={{ color: 'red' }} />}
                >
                <Tooltip title="Delete Selected Translations">
                    <Button
                        block
                        type="default"
                        className="table-btn"
                        title="Delete Selected Translations"
                        disabled={selectedRowKeys.length === 0}
                    >
                        <TrashIcon className="size-5" />
                    </Button>
                </Tooltip>
            </Popconfirm>
            ),
            className: '!p-0',
            dataIndex: 'delete',
            width: '5%',
        },
    ];

    const data: DataType[] = translations.map((translation: Translation) => ({
        key: translation.id.toString(),
        source: translation.source,
        name: (
            <Link
                href={
                    translation.source
                        ? route('ltu.source_translation')
                        : route('ltu.phrases.index', [translation.id])
                }
                className="w-full h-full flex p-4"
            >
                <LanguageTag language={translation.language} />
            </Link>
        ),
        progress: (
            <Link
                href={
                    translation.source
                        ? route('ltu.source_translation')
                        : route('ltu.phrases.index', [translation.id])
                }
                className="w-full h-full flex p-4"
            >
                {translation.source ? (
                    <span className="text-gray-500">
                        {`${translation.phrases_count} source keys`}
                    </span>
                ) : (
                    <Tooltip
                        title={(
                            <div className="flex items-center gap-2">
                                <div className="size-2 bg-green-600 rounded-full"></div>
                                <span>{`${translation.progress}% strings translated`}</span>
                            </div>
                        )}
                    >
                        <Progress percent={translation.progress} showInfo={false} />
                    </Tooltip>
                )}
            </Link>
        ),
        add: (
            <Link
                href={
                    translation.source
                        ? route('ltu.source_translation')
                        : route('ltu.phrases.index', [translation.id])
                }
            >
                {translation.source ? (
                    <Tooltip title="Manage Keys">
                        <Button
                            block
                            type="default"
                            className="table-btn"
                        >
                            <Cog6ToothIcon className="size-5" />
                        </Button>
                    </Tooltip>
                ) : (
                    translation.progress === 100 ? (
                        <Tooltip title="Translation Complete">
                            <Button
                                block
                                type="default"
                                className="table-btn"
                            >
                                <IconSmile className="size-5" />
                            </Button>
                        </Tooltip>
                    ) : (
                        <Tooltip title="Translate">
                            <Button
                                block
                                type="default"
                                className="table-btn"
                            >
                                <IconTranslate className="size-5" />
                            </Button>
                        </Tooltip>
                    )
                )}
            </Link>
        ),
        delete: (
            <Popconfirm
                okText="Yes"
                cancelText="No"
                placement="left"
                title="Delete Language"
                onConfirm={() => confirmed(translation.id)}
                okButtonProps={{ loading: confirmLoading }}
                cancelButtonProps={{ disabled: confirmLoading }}
                description="Are you sure to delete this language?"
                icon={<QuestionCircleOutlined style={{ color: 'red' }} />}
            >
                <Tooltip
                    title={
                        translation.source
                            ? "Source cannot be deleted"
                            : "Delete"
                    }
                >
                    <Button
                        block
                        type="default"
                        title="Delete"
                        disabled={translation.source}
                        className="table-btn"
                    >
                        <TrashIcon className="size-5" />
                    </Button>
                </Tooltip>
            </Popconfirm>
        ),
    }));

    return (
        <DashboardLayout
            user={auth.user}
            header={
                <h2 className="text-lg font-semibold text-gray-600">
                    Translations
                </h2>
            }
        >
            <Head title="Translations" />

            <div className="mb-4 flex justify-end gap-2">
                <Button
                    type="primary"
                    onClick={() => setIsPublishModalOpen(true)}
                    disabled={!canPublish}
                >
                    Publish Translations
                </Button>
                <Button
                    type="default"
                    onClick={() => setIsModalOpen(true)}
                    icon={<PlusIcon className="size-5" />}
                >
                    Add Language
                </Button>
            </div>

            <div className="rounded-lg shadow overflow-hidden mx-auto">
                <Table
                    bordered
                    columns={columns}
                    dataSource={data}
                    pagination={false}
                    footer={() => (
                        <div className="flex justify-end gap-4">
                            <Button
                                block
                                size="large"
                                type="dashed"
                                iconPosition="end"
                                icon={<PlusIcon className="size-6" />}
                                onClick={() => setIsModalOpen(true)}
                                className="border-none py-7 text-sm text-gray-500 uppercase font-medium justify-between"
                            >
                                Add new language
                            </Button>
                        </div>
                    )}
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
                                        There are no languages to translate your project to. <br /> Add the first one and start translating.
                                    </Typography.Text>
                                }
                            >
                                <Button
                                    size="large"
                                    type="primary"
                                    onClick={() => setIsModalOpen(true)}
                                    icon={<PlusIcon className="size-5" />}
                                >
                                    Add languages
                                </Button>
                            </Empty>
                        )
                    }}
                />
            </div>

            <AddTranslations
                languages={languages}
                isModalOpen={isModalOpen}
                setIsModalOpen={setIsModalOpen}
            />

            <PublishTranslations
                isOpen={isPublishModalOpen}
                onClose={() => setIsPublishModalOpen(false)}
                canPublish={canPublish}
                isProductionEnv={isProductionEnv}
            />
        </DashboardLayout>
    );
};

export default TranslationsIndex;
