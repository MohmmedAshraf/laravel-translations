import { Head, useForm } from '@inertiajs/react';
import { show as loginShow } from '@/actions/Outhebox/Translations/Http/Controllers/Auth/LoginController';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { store } from '@/routes/ltu/register';

export default function Register() {
    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(store.url(), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout
            title="Create a contributor account"
            description="Enter your details below to create your account"
        >
            <Head title="Register" />

            <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                <div className="grid gap-6">
                    <Field data-invalid={!!form.errors.name}>
                        <FieldLabel htmlFor="name">Name</FieldLabel>
                        <Input
                            id="name"
                            type="text"
                            value={form.data.name}
                            onChange={(e) =>
                                form.setData('name', e.target.value)
                            }
                            required
                            autoFocus
                            autoComplete="name"
                            placeholder="Full name"
                            aria-invalid={!!form.errors.name}
                        />
                        <FieldError>{form.errors.name}</FieldError>
                    </Field>

                    <Field data-invalid={!!form.errors.email}>
                        <FieldLabel htmlFor="email">Email address</FieldLabel>
                        <Input
                            id="email"
                            type="email"
                            value={form.data.email}
                            onChange={(e) =>
                                form.setData('email', e.target.value)
                            }
                            required
                            autoComplete="email"
                            placeholder="email@example.com"
                            aria-invalid={!!form.errors.email}
                        />
                        <FieldError>{form.errors.email}</FieldError>
                    </Field>

                    <Field data-invalid={!!form.errors.password}>
                        <FieldLabel htmlFor="password">Password</FieldLabel>
                        <Input
                            id="password"
                            type="password"
                            value={form.data.password}
                            onChange={(e) =>
                                form.setData('password', e.target.value)
                            }
                            required
                            autoComplete="new-password"
                            placeholder="Password"
                            aria-invalid={!!form.errors.password}
                        />
                        <FieldError>{form.errors.password}</FieldError>
                    </Field>

                    <Field data-invalid={!!form.errors.password_confirmation}>
                        <FieldLabel htmlFor="password_confirmation">
                            Confirm password
                        </FieldLabel>
                        <Input
                            id="password_confirmation"
                            type="password"
                            value={form.data.password_confirmation}
                            onChange={(e) =>
                                form.setData(
                                    'password_confirmation',
                                    e.target.value,
                                )
                            }
                            required
                            autoComplete="new-password"
                            placeholder="Confirm password"
                            aria-invalid={!!form.errors.password_confirmation}
                        />
                        <FieldError>
                            {form.errors.password_confirmation}
                        </FieldError>
                    </Field>

                    <Button
                        type="submit"
                        className="mt-2 w-full"
                        disabled={form.processing}
                    >
                        {form.processing && <Spinner />}
                        Create account
                    </Button>
                </div>

                <div className="text-center text-sm text-muted-foreground">
                    Already have an account?{' '}
                    <TextLink href={loginShow.url()}>Log in</TextLink>
                </div>
            </form>
        </AuthLayout>
    );
}
