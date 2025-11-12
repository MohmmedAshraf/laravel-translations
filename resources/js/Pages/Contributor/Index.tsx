import React, { useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { PageProps, Contributor, Invite } from "@/types";
import {
    Button,
    Table,
    TableColumnsType,
    TableProps,
    Tabs,
    Tooltip,
    Popconfirm,
    message,
    Empty,
    Typography,
    Pagination,
} from "antd";
import { PlusIcon, TrashIcon } from "@heroicons/react/24/outline";
import { QuestionCircleOutlined } from "@ant-design/icons";
import IconEmptyTranslations from "@/Components/Icons/IconEmptyTranslations";

interface PaginationMeta {
    total: number;
    per_page: number;
    current_page: number;
}

interface PaginationLinks {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
}

interface ContributorDataType {
    key: React.Key;
    id: number;
    name: React.ReactNode;
    email: React.ReactNode;
    role: React.ReactNode;
    delete: React.ReactNode;
}

interface InviteDataType {
    key: React.Key;
    id: number;
    email: React.ReactNode;
    role: React.ReactNode;
    createdAt: React.ReactNode;
    delete: React.ReactNode;
}

interface Props extends PageProps {
    invited: {
        data: Invite[];
        meta: PaginationMeta;
        links: PaginationLinks;
    };
    contributors: {
        data: Contributor[];
        meta: PaginationMeta;
        links: PaginationLinks;
    };
}

const ContributorIndex: React.FC<Props> = ({ auth, invited, contributors }) => {
    const [confirmLoading, setConfirmLoading] = useState(false);

    const handleDeleteContributor = async (id: number) => {
        try {
            setConfirmLoading(true);
            const response = await window.axios.delete<{ message: string }>(
                route('ltu.contributors.delete', id)
            );

            if (response.status === 200) {
                message.success('Contributor deleted successfully');
                router.visit(route('ltu.contributors.index'), {
                    replace: true,
                    only: ['contributors', 'invited'],
                });
            }
        } catch (error) {
            message.error('Failed to delete contributor');
        } finally {
            setConfirmLoading(false);
        }
    };

    const handleDeleteInvite = async (id: number) => {
        try {
            setConfirmLoading(true);
            const response = await window.axios.delete<{ message: string }>(
                route('ltu.contributors.invite.delete', id)
            );

            if (response.status === 200) {
                message.success('Invite deleted successfully');
                router.visit(route('ltu.contributors.index'), {
                    replace: true,
                    only: ['contributors', 'invited'],
                });
            }
        } catch (error) {
            message.error('Failed to delete invite');
        } finally {
            setConfirmLoading(false);
        }
    };

    // Contributors Table
    const contributorColumns: TableColumnsType<ContributorDataType> = [
        {
            title: "Name",
            dataIndex: "name",
            width: "30%",
        },
        {
            title: "Email",
            dataIndex: "email",
            width: "40%",
        },
        {
            title: "Role",
            dataIndex: "role",
            width: "20%",
        },
        {
            title: "",
            dataIndex: "delete",
            width: "10%",
            align: "center",
        },
    ];

    const contributorData: ContributorDataType[] = contributors.data.map((contributor: Contributor) => ({
        key: contributor.id.toString(),
        id: contributor.id,
        name: <span className="font-medium">{contributor.name}</span>,
        email: <span className="text-gray-600">{contributor.email}</span>,
        role: (
            <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {contributor.role?.label || 'Unknown'}
            </span>
        ),
        delete: (
            <Popconfirm
                title="Delete Contributor"
                description="Are you sure to delete this contributor?"
                onConfirm={() => handleDeleteContributor(contributor.id)}
                okButtonProps={{ loading: confirmLoading }}
                cancelButtonProps={{ disabled: confirmLoading }}
                icon={<QuestionCircleOutlined style={{ color: 'red' }} />}
                okText="Yes"
                cancelText="No"
            >
                <Tooltip title={auth.user?.id === contributor.id ? "You cannot delete yourself" : "Delete"}>
                    <Button
                        type="text"
                        danger
                        icon={<TrashIcon className="size-4" />}
                        disabled={auth.user?.id === contributor.id || confirmLoading}
                    />
                </Tooltip>
            </Popconfirm>
        ),
    }));

    // Invites Table
    const inviteColumns: TableColumnsType<InviteDataType> = [
        {
            title: "Email",
            dataIndex: "email",
            width: "40%",
        },
        {
            title: "Role",
            dataIndex: "role",
            width: "25%",
        },
        {
            title: "Sent",
            dataIndex: "createdAt",
            width: "25%",
        },
        {
            title: "",
            dataIndex: "delete",
            width: "10%",
            align: "center",
        },
    ];

    const inviteData: InviteDataType[] = invited.data.map((invite: Invite) => ({
        key: invite.id.toString(),
        id: invite.id,
        email: <span className="font-medium">{invite.email}</span>,
        role: (
            <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                {invite.role?.label || 'Unknown'}
            </span>
        ),
        createdAt: <span className="text-gray-600">{new Date(invite.created_at).toLocaleDateString()}</span>,
        delete: (
            <Popconfirm
                title="Delete Invite"
                description="Are you sure to delete this invite?"
                onConfirm={() => handleDeleteInvite(invite.id)}
                okButtonProps={{ loading: confirmLoading }}
                cancelButtonProps={{ disabled: confirmLoading }}
                icon={<QuestionCircleOutlined style={{ color: 'red' }} />}
                okText="Yes"
                cancelText="No"
            >
                <Tooltip title="Delete">
                    <Button
                        type="text"
                        danger
                        icon={<TrashIcon className="size-4" />}
                        disabled={confirmLoading}
                    />
                </Tooltip>
            </Popconfirm>
        ),
    }));

    const tabItems = [
        {
            key: "contributors",
            label: "Contributors",
            children: (
                <div className="bg-white rounded-lg shadow overflow-hidden">
                    <Table
                        columns={contributorColumns}
                        dataSource={contributorData}
                        pagination={{
                            total: contributors.meta.total,
                            pageSize: contributors.meta.per_page,
                            current: contributors.meta.current_page,
                            showSizeChanger: true,
                            position: ['bottomCenter'],
                            showTotal: (total, range) =>
                                `${range[0]}-${range[1]} of ${total} items`,
                        }}
                        onChange={(pagination) => {
                            router.get(
                                route('ltu.contributors.index'),
                                {
                                    page: pagination.current,
                                    per_page: pagination.pageSize,
                                },
                                {
                                    preserveState: true,
                                    only: ['contributors'],
                                }
                            );
                        }}
                        locale={{
                            emptyText: (
                                <Empty
                                    className="py-16"
                                    image={<IconEmptyTranslations className="size-20 mb-4 fill-gray-300" />}
                                    description={
                                        <Typography.Text>
                                            No contributors yet. Invite someone to get started.
                                        </Typography.Text>
                                    }
                                />
                            ),
                        }}
                    />
                </div>
            ),
        },
        {
            key: "invited",
            label: `Pending Invites (${invited.data.length})`,
            children: (
                <div className="bg-white rounded-lg shadow overflow-hidden">
                    <Table
                        columns={inviteColumns}
                        dataSource={inviteData}
                        pagination={{
                            total: invited.meta.total,
                            pageSize: invited.meta.per_page,
                            current: invited.meta.current_page,
                            showSizeChanger: true,
                            position: ['bottomCenter'],
                            showTotal: (total, range) =>
                                `${range[0]}-${range[1]} of ${total} items`,
                        }}
                        onChange={(pagination) => {
                            router.get(
                                route('ltu.contributors.index'),
                                {
                                    page: pagination.current,
                                    per_page: pagination.pageSize,
                                },
                                {
                                    preserveState: true,
                                    only: ['invited'],
                                }
                            );
                        }}
                        locale={{
                            emptyText: (
                                <Empty
                                    className="py-16"
                                    image={<IconEmptyTranslations className="size-20 mb-4 fill-gray-300" />}
                                    description={
                                        <Typography.Text>
                                            No pending invites.
                                        </Typography.Text>
                                    }
                                />
                            ),
                        }}
                    />
                </div>
            ),
        },
    ];

    return (
        <DashboardLayout
            user={auth.user}
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-lg font-semibold text-gray-600">
                        Contributors
                    </h2>
                    <Link href={route('ltu.contributors.invite')}>
                        <Button
                            type="primary"
                            icon={<PlusIcon className="size-5" />}
                        >
                            Invite Contributor
                        </Button>
                    </Link>
                </div>
            }
        >
            <Head title="Contributors" />

            <Tabs items={tabItems} />
        </DashboardLayout>
    );
};

export default ContributorIndex;
