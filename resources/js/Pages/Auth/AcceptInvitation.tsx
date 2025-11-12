import React from "react";
import { Head, useForm } from "@inertiajs/react";
import { Button, Form, Input, message } from "antd";

interface Props {
    email: string;
    token: string;
}

const AcceptInvitation: React.FC<Props> = ({ email, token }) => {
    const { data, setData, post, processing, errors } = useForm({
        name: "",
        email: email,
        password: "",
        password_confirmation: "",
        token: token,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (data.password !== data.password_confirmation) {
            message.error("Passwords do not match");
            return;
        }

        post(route("ltu.invitation.accept.store"), {
            onSuccess: () => {
                message.success("Account created successfully");
            },
            onError: () => {
                message.error("Failed to accept invitation");
            },
            onFinish: () => {
                setData({
                    ...data,
                    password: "",
                    password_confirmation: "",
                });
            },
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8">
                <div>
                    <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Accept Invitation
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        You have been invited to join the team!
                    </p>
                </div>

                <Head title="Accept Invitation" />

                <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
                    <Form layout="vertical">
                        <Form.Item
                            label="Name"
                            name="name"
                            validateStatus={errors.name ? "error" : ""}
                            help={errors.name}
                            required
                        >
                            <Input
                                type="text"
                                placeholder="Enter your name"
                                value={data.name}
                                onChange={(e) => setData("name", e.target.value)}
                                autoFocus
                                required
                                size="large"
                            />
                        </Form.Item>

                        <Form.Item
                            label="Email Address"
                            name="email"
                            validateStatus={errors.email ? "error" : ""}
                            help={errors.email}
                        >
                            <Input
                                type="email"
                                disabled
                                value={data.email}
                                size="large"
                                className="bg-gray-100"
                            />
                        </Form.Item>

                        <Form.Item
                            label="Password"
                            name="password"
                            validateStatus={errors.password ? "error" : ""}
                            help={errors.password}
                            required
                        >
                            <Input.Password
                                placeholder="Create a password"
                                value={data.password}
                                onChange={(e) => setData("password", e.target.value)}
                                required
                                size="large"
                            />
                        </Form.Item>

                        <Form.Item
                            label="Confirm Password"
                            name="password_confirmation"
                            validateStatus={
                                errors.password_confirmation ? "error" : ""
                            }
                            help={errors.password_confirmation}
                            required
                        >
                            <Input.Password
                                placeholder="Confirm your password"
                                value={data.password_confirmation}
                                onChange={(e) =>
                                    setData("password_confirmation", e.target.value)
                                }
                                required
                                size="large"
                            />
                        </Form.Item>

                        <Button
                            htmlType="submit"
                            type="primary"
                            block
                            size="large"
                            loading={processing}
                            disabled={processing}
                        >
                            Accept Invitation
                        </Button>
                    </Form>

                    <div className="text-center mt-4">
                        <a
                            href={route("ltu.login")}
                            className="text-sm font-medium text-blue-600 hover:text-blue-500"
                        >
                            Already have an account? Sign in
                        </a>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default AcceptInvitation;
