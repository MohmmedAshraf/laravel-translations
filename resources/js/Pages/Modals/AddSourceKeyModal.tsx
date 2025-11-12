import React, { useState } from "react";
import { useForm } from "@inertiajs/react";
import { Button, Form, Input, Modal, Select, message } from "antd";
import { ExclamationCircleIcon } from "@heroicons/react/20/solid";
import IconKey from "@/Components/Icons/IconKey";
import { TranslationFile } from "@/types";

interface AddSourceKeyModalProps {
    files: TranslationFile[];
    isOpen: boolean;
    onClose: () => void;
}

const AddSourceKeyModal: React.FC<AddSourceKeyModalProps> = ({
    files,
    isOpen,
    onClose,
}) => {
    const [componentKey, setComponentKey] = useState(0);

    const { data, setData, post, processing, errors } = useForm({
        key: "",
        file: "",
        content: "",
    });

    const translationFiles = files.map((file) => ({
        value: file.id,
        label: `${file.name}.${file.extension}`,
    }));

    const handleSubmit = () => {
        post(route("ltu.source_translation.store_source_key"), {
            preserveScroll: true,
            onSuccess: () => {
                message.success("Source key created successfully");
                onClose();
                resetForm();
            },
            onError: () => {
                message.error("Failed to create source key");
            },
        });
    };

    const resetForm = () => {
        setData({
            key: "",
            file: "",
            content: "",
        });
        setComponentKey((prev) => prev + 1);
    };

    const handleCancel = () => {
        onClose();
        resetForm();
    };

    return (
        <Modal
            title="Add New Key"
            open={isOpen}
            onCancel={handleCancel}
            footer={[
                <Button key="cancel" onClick={handleCancel}>
                    Close
                </Button>,
                <Button
                    key="submit"
                    type="primary"
                    onClick={handleSubmit}
                    loading={processing}
                    disabled={processing}
                >
                    Create
                </Button>,
            ]}
        >
            <div className="mb-6">
                <div className="flex gap-4">
                    <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-gray-50">
                        <IconKey className="size-6 text-gray-400" />
                    </div>
                    <div className="flex-1">
                        <h3 className="text-base font-semibold text-gray-900">
                            Add New Key
                        </h3>
                        <p className="mt-1 text-sm text-gray-600">
                            Add a new key to your source language.
                        </p>
                    </div>
                </div>
            </div>

            <Form
                key={componentKey}
                layout="vertical"
                initialValues={data}
                onFieldsChange={(changedFields) => {
                    changedFields.forEach((item) => {
                        setData(item.name[0] as string, item.value);
                    });
                }}
            >
                <Form.Item
                    label="Select file"
                    name="file"
                    validateStatus={errors.file ? "error" : ""}
                    help={errors.file}
                    required
                >
                    <Select
                        placeholder="Select translation file"
                        options={translationFiles}
                        value={data.file || undefined}
                        onChange={(value) => setData("file", value)}
                        size="large"
                    />
                </Form.Item>

                <Form.Item
                    label="Source key"
                    name="key"
                    validateStatus={errors.key ? "error" : ""}
                    help={errors.key}
                    required
                >
                    <Input
                        placeholder="Enter key"
                        value={data.key}
                        onChange={(e) => setData("key", e.target.value)}
                        size="large"
                    />
                    <div className="mt-2 flex items-center gap-1 text-sm text-gray-500">
                        <ExclamationCircleIcon className="size-4" />
                        <span>You can use dot notation (.) to create nested keys.</span>
                    </div>
                </Form.Item>

                <Form.Item
                    label="Source content"
                    name="content"
                    validateStatus={errors.content ? "error" : ""}
                    help={errors.content}
                    required
                >
                    <Input.TextArea
                        rows={4}
                        placeholder="Enter translation content for this key."
                        value={data.content}
                        onChange={(e) => setData("content", e.target.value)}
                    />
                </Form.Item>
            </Form>
        </Modal>
    );
};

export default AddSourceKeyModal;
