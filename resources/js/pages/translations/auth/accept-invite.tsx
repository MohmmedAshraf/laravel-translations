import { Head, useForm } from '@inertiajs/react';
import { accept } from '@/actions/Outhebox/Translations/Http/Controllers/Auth/InviteController';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';

type Props = {
    token: string;
    contributor: {
        name: string;
        email: string;
    };
};

export default function AcceptInvite({ token, contributor }: Props) {
    const form = useForm({
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(accept.url({ token }), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout
            title="Accept your invitation"
            description="Set a password to complete your account setup"
        >
            <Head title="Accept Invitation" />

            <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                <div className="grid gap-6">
                    <Field>
                        <FieldLabel htmlFor="name">Name</FieldLabel>
                        <Input
                            id="name"
                            type="text"
                            value={contributor.name}
                            disabled
                        />
                    </Field>

                    <Field>
                        <FieldLabel htmlFor="email">Email address</FieldLabel>
                        <Input
                            id="email"
                            type="email"
                            value={contributor.email}
                            disabled
                        />
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
                            autoFocus
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
                        Accept invitation
                    </Button>
                </div>
            </form>
        </AuthLayout>
    );
}
