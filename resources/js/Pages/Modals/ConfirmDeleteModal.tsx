import React from "react";
import { Popconfirm, Button, Tooltip } from "antd";
import { QuestionCircleOutlined } from "@ant-design/icons";
import { TrashIcon } from "@heroicons/react/24/outline";

interface ConfirmDeleteModalProps {
    confirmLoading: boolean;
    callback: () => void;
    source: boolean;
}

const ConfirmDeleteModal: React.FC<ConfirmDeleteModalProps> = ({ confirmLoading, callback, source }) => (
    <Popconfirm
        okText="Yes"
        cancelText="No"
        placement="left"
        title="Delete Language"
        onConfirm={() => callback}
        okButtonProps={{ loading: confirmLoading }}
        cancelButtonProps={{ disabled: confirmLoading }}
        description="Are you sure to delete this language?"
        icon={<QuestionCircleOutlined style={{ color: 'red' }} />}
    >
        <Tooltip
            title={source ? "Source cannot be deleted" : "Delete"}
        >
            <Button
                block
                type="default"
                title="Delete"
                disabled={source}
                className="table-btn"
            >
                <TrashIcon className="size-5" />
            </Button>
        </Tooltip>
    </Popconfirm>
);

export default ConfirmDeleteModal;