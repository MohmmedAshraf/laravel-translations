import { useEffect } from "react";
import {Head, useForm} from "@inertiajs/react";
import GuestLayout from "@/Layouts/GuestLayout";
import {Button, Form, Input, Typography} from "antd";

export default function ResetPassword({ token, email }: { token: string, email: string }) {
    const { Title } = Typography;
    const { data, setData, post, processing, errors, reset } = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit = () => {
        post(route('ltu.password.update'));
    };

    return (
        <GuestLayout
            title="Reset your password"
        >
            <Head title="Reset Password"/>

            <div className="mt-4">
                <Form
                    name="basic"
                    layout='vertical'
                    initialValues={data}
                    onFieldsChange={(changedFields) => {
                        changedFields.forEach(item => {
                            setData(item.name[0], item.value);
                        })
                    }}
                    onFinish={submit}
                    autoComplete="off"
                >
                    <Form.Item
                        label="Password"
                        name="password"
                        validateStatus={errors.password && 'error'}
                        help={errors.password}
                    >
                        <Input.Password size="large" placeholder="Enter your password"/>
                    </Form.Item>

                    <Form.Item
                        label="Confirm Password"
                        name="password_confirmation"
                        validateStatus={errors.password_confirmation && 'error'}
                        help={errors.password_confirmation}
                    >
                        <Input.Password size="large" placeholder="Confirm your password"/>
                    </Form.Item>

                    <Button className="mt-1" size="large" type="primary" htmlType="submit" loading={processing} block>
                        Reset Password
                    </Button>
                </Form>
            </div>
        </GuestLayout>
    );
}
