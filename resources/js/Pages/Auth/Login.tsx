import { useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import {Alert, Button, Checkbox, Form, Input} from 'antd';
import {Head, Link, useForm, usePage} from '@inertiajs/react';

const Login = ({ status }: { status?: string, canResetPassword: boolean }) => {
    const { flash } = usePage().props as unknown as { flash: { message?: string } };

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = () => {
        post(route('ltu.login.attempt'));
    };

    return (
        <GuestLayout
            title="Sign in to your account"
        >
            <Head title="Sign in" />

            <div className="mx-auto w-full max-w-sm lg:w-96">
                {(flash.message || status) && (
                    <Alert
                        message={flash.message || status}
                        type={status ? 'success' : 'error'}
                        showIcon
                        className="mt-4"
                    />
                )}

                <div className="mt-6">
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
                            name="email"
                            label="Email Address"
                            validateStatus={errors.email && 'error'}
                            help={errors.email}
                        >
                            <Input
                                type="email"
                                size="large"
                                placeholder="Enter your email address"
                                autoComplete="username"
                            />
                        </Form.Item>

                        <Form.Item
                            name="password"
                            label="Password"
                            validateStatus={errors.password && 'error'}
                            help={errors.password}
                        >
                            <Input.Password
                                size="large"
                                placeholder="Enter your password"
                                autoComplete="current-password"
                            />
                        </Form.Item>

                        <div className="flex items-center justify-between">
                            <Form.Item
                                name="remember"
                                valuePropName="checked"
                                className="mb-0"
                            >
                                <Checkbox>Remember me</Checkbox>
                            </Form.Item>

                            <Link href={route('ltu.password.request')} className="link">
                                Forgot your password?
                            </Link>
                        </div>

                        <Button className="mt-4" size="large" type="primary" htmlType="submit" loading={processing} block>
                            Sign in
                        </Button>
                    </Form>
                </div>
            </div>
        </GuestLayout>
    );
}

export default Login;
