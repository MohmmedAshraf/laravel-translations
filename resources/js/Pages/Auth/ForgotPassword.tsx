import {Head, useForm} from '@inertiajs/react';
import {Alert, Button, Form, Input} from "antd";
import GuestLayout from '@/Layouts/GuestLayout';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit = () => {
        post(route('ltu.password.email'));
    };

    return (
        <GuestLayout
            title="Forgot your password?"
            subtitle="Don't worry, we got your back, Just let us know your email address and we will send reset instructions."
        >
            <Head title="Forgot Password"/>

            {status && (
                <Alert message={status} type="success" showIcon className="mt-4"/>
            )}

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
                        label="Email Address"
                        name="email"
                        validateStatus={errors.email && 'error'}
                        help={errors.email}
                    >
                        <Input type="email" size="large" placeholder="user@example.com"/>
                    </Form.Item>

                    <Button className="mt-1" size="large" type="primary" htmlType="submit" loading={processing} block>
                        Send Reset Instructions
                    </Button>
                </Form>

                <div className="mt-6">
                    <div className="relative">
                        <div aria-hidden="true" className="absolute inset-0 flex items-center">
                            <div className="w-full border-t border-gray-200"/>
                        </div>
                        <div className="relative flex justify-center text-sm font-medium leading-6">
                            <span className="bg-white px-6 text-gray-900">Remember your password?</span>
                        </div>
                    </div>

                    <div className="mt-6">
                        <Button href={route('ltu.login')} size="large" type="default" htmlType="button" disabled={processing} block>
                            Return to login
                        </Button>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
