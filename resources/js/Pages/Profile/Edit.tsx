import React, { useState } from "react";
import { Head, useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { PageProps } from "@/types";
import { Button, Form, Input, message, Card, Divider } from "antd";

interface Props extends PageProps {
    mustVerifyEmail?: boolean;
    status?: string;
}

const EditProfile: React.FC<Props> = ({ auth, mustVerifyEmail, status }) => {
    const [profileKey, setProfileKey] = useState(0);
    const [passwordKey, setPasswordKey] = useState(0);

    // Profile Form
    const {
        data: profileData,
        setData: setProfileData,
        patch: patchProfile,
        processing: profileProcessing,
        errors: profileErrors,
    } = useForm({
        name: auth.user?.name || "",
        email: auth.user?.email || "",
    });

    const handleUpdateProfile = () => {
        patchProfile(route("ltu.profile.update"), {
            onSuccess: () => {
                message.success("Profile updated successfully");
                setProfileKey((prev) => prev + 1);
            },
            onError: () => {
                message.error("Failed to update profile");
            },
        });
    };

    // Password Form
    const {
        data: passwordData,
        setData: setPasswordData,
        put: putPassword,
        processing: passwordProcessing,
        errors: passwordErrors,
    } = useForm({
        current_password: "",
        password: "",
        password_confirmation: "",
    });

    const handleUpdatePassword = () => {
        if (
            passwordData.password !== passwordData.password_confirmation
        ) {
            message.error("Passwords do not match");
            return;
        }

        putPassword(route("ltu.profile.password.update"), {
            onSuccess: () => {
                message.success("Password updated successfully");
                setPasswordKey((prev) => prev + 1);
                setPasswordData({
                    current_password: "",
                    password: "",
                    password_confirmation: "",
                });
            },
            onError: () => {
                message.error("Failed to update password");
            },
        });
    };

    return (
        <DashboardLayout
            user={auth.user}
            header={
                <h2 className="text-lg font-semibold text-gray-600">
                    Profile Settings
                </h2>
            }
        >
            <Head title="Profile Settings" />

            <div className="max-w-2xl mx-auto py-8 space-y-6">
                {/* Profile Information Card */}
                <Card>
                    <div className="mb-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">
                            Profile Information
                        </h3>
                        <p className="text-sm text-gray-600">
                            Update your profile information below.
                        </p>
                    </div>

                    {status === "profile-updated" && (
                        <div className="mb-4 p-4 bg-green-50 border border-green-200 rounded">
                            <p className="text-sm text-green-800">
                                Profile updated successfully!
                            </p>
                        </div>
                    )}

                    <Form key={profileKey} layout="vertical">
                        <Form.Item
                            label="Name"
                            validateStatus={profileErrors.name ? "error" : ""}
                            help={profileErrors.name}
                            required
                        >
                            <Input
                                type="text"
                                placeholder="Your name"
                                value={profileData.name}
                                onChange={(e) =>
                                    setProfileData("name", e.target.value)
                                }
                                size="large"
                            />
                        </Form.Item>

                        <Form.Item
                            label="Email Address"
                            validateStatus={profileErrors.email ? "error" : ""}
                            help={profileErrors.email}
                            required
                        >
                            <Input
                                type="email"
                                placeholder="your@email.com"
                                value={profileData.email}
                                onChange={(e) =>
                                    setProfileData("email", e.target.value)
                                }
                                size="large"
                            />
                        </Form.Item>

                        {mustVerifyEmail && auth.user?.email_verified_at === null && (
                            <div className="p-4 bg-amber-50 border border-amber-200 rounded">
                                <p className="text-sm text-amber-800 mb-2">
                                    Your email address is unverified.
                                </p>
                                <a
                                    href={route(
                                        "verification.send",
                                        []
                                    )}
                                    className="text-sm font-medium text-blue-600 hover:text-blue-500"
                                >
                                    Click here to re-send the verification email.
                                </a>
                            </div>
                        )}

                        <div className="mt-6">
                            <Button
                                type="primary"
                                onClick={handleUpdateProfile}
                                loading={profileProcessing}
                                disabled={profileProcessing}
                            >
                                Save Changes
                            </Button>
                        </div>
                    </Form>
                </Card>

                {/* Password Card */}
                <Card>
                    <div className="mb-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">
                            Change Password
                        </h3>
                        <p className="text-sm text-gray-600">
                            Ensure your account is using a long, random password to stay secure.
                        </p>
                    </div>

                    {status === "password-updated" && (
                        <div className="mb-4 p-4 bg-green-50 border border-green-200 rounded">
                            <p className="text-sm text-green-800">
                                Password updated successfully!
                            </p>
                        </div>
                    )}

                    <Form key={passwordKey} layout="vertical">
                        <Form.Item
                            label="Current Password"
                            validateStatus={
                                passwordErrors.current_password ? "error" : ""
                            }
                            help={passwordErrors.current_password}
                            required
                        >
                            <Input.Password
                                placeholder="Your current password"
                                value={passwordData.current_password}
                                onChange={(e) =>
                                    setPasswordData(
                                        "current_password",
                                        e.target.value
                                    )
                                }
                                size="large"
                            />
                        </Form.Item>

                        <Form.Item
                            label="New Password"
                            validateStatus={passwordErrors.password ? "error" : ""}
                            help={passwordErrors.password}
                            required
                        >
                            <Input.Password
                                placeholder="New password"
                                value={passwordData.password}
                                onChange={(e) =>
                                    setPasswordData("password", e.target.value)
                                }
                                size="large"
                            />
                        </Form.Item>

                        <Form.Item
                            label="Confirm Password"
                            validateStatus={
                                passwordErrors.password_confirmation ? "error" : ""
                            }
                            help={passwordErrors.password_confirmation}
                            required
                        >
                            <Input.Password
                                placeholder="Confirm new password"
                                value={passwordData.password_confirmation}
                                onChange={(e) =>
                                    setPasswordData(
                                        "password_confirmation",
                                        e.target.value
                                    )
                                }
                                size="large"
                            />
                        </Form.Item>

                        <div className="mt-6">
                            <Button
                                type="primary"
                                onClick={handleUpdatePassword}
                                loading={passwordProcessing}
                                disabled={passwordProcessing}
                            >
                                Update Password
                            </Button>
                        </div>
                    </Form>
                </Card>
            </div>
        </DashboardLayout>
    );
};

export default EditProfile;
