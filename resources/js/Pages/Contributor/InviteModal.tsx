import React, { useState } from "react";
import { Head, useForm, Link } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { PageProps } from "@/types";
import { Button, Form, Input, Modal, Radio, message } from "antd";

interface Role {
    value: number;
    label: string;
    description?: string;
}

interface Props extends PageProps {
    roles: Role[];
}

const InviteContributor: React.FC<Props> = ({ auth, roles }) => {
    const [isOpen, setIsOpen] = useState(true);
    const [componentKey, setComponentKey] = useState(0);

    const { data, setData, post, processing, errors } = useForm({
        email: "",
        role: roles[1]?.value || roles[0]?.value || 1,
    });

    const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email);
    const canSubmit = data.email.trim().length > 0 && isValidEmail;

    const handleSubmit = () => {
        if (!canSubmit) return;

        post(route("ltu.contributors.invite.store"), {
            onSuccess: () => {
                message.success("Invitation sent successfully");
                setIsOpen(false);
                setComponentKey((prev) => prev + 1);
            },
            onError: () => {
                message.error("Failed to send invitation");
            },
        });
    };

    return (
        <Modal
            title="Invite Contributor by e-mail"
            open={isOpen}
            onCancel={() => setIsOpen(false)}
            footer={[
                <Button key="close" onClick={() => setIsOpen(false)}>
                    Close
                </Button>,
                <Button
                    key="submit"
                    type="primary"
                    onClick={handleSubmit}
                    loading={processing}
                    disabled={!canSubmit || processing}
                >
                    Send Invitation
                </Button>,
            ]}
            onOk={handleSubmit}
        >
            <div className="mb-6">
                <p className="text-gray-600">
                    Invite a new contributor to your project.
                </p>
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
                    label="E-mail address"
                    name="email"
                    validateStatus={errors.email ? "error" : ""}
                    help={errors.email}
                    required
                >
                    <Input
                        type="email"
                        placeholder="contributor@example.com"
                        size="large"
                        value={data.email}
                        onChange={(e) => setData("email", e.target.value)}
                    />
                </Form.Item>

                <Form.Item label="Role" name="role" required>
                    <Radio.Group
                        value={data.role}
                        onChange={(e) => setData("role", e.target.value)}
                    >
                        <div className="space-y-3">
                            {roles.map((role) => (
                                <Radio key={role.value} value={role.value}>
                                    <div className="ml-2">
                                        <div className="font-medium text-gray-900">
                                            {role.label}
                                        </div>
                                        {role.description && (
                                            <div className="text-sm text-gray-500">
                                                {role.description}
                                            </div>
                                        )}
                                    </div>
                                </Radio>
                            ))}
                        </div>
                    </Radio.Group>
                </Form.Item>
            </Form>
        </Modal>
    );
};

export default InviteContributor;
