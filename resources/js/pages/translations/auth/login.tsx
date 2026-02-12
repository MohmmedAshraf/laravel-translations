import { Head, useForm } from '@inertiajs/react';
import { show as registerShow } from '@/actions/Outhebox/Translations/Http/Controllers/Auth/RegisterController';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { store } from '@/routes/ltu/login';

type Props = {
    canRegister: boolean;
};

export default function Login({ canRegister }: Props) {
    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(store.url(), {
            onFinish: () => form.reset('password'),
        });
    };

    return (
        <AuthLayout
            title="Log in to Translations"
            description="Enter your email and password below to log in"
        >
            <Head title="Log in" />

            <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                <div className="grid gap-6">
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
                            autoFocus
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
                            autoComplete="current-password"
                            placeholder="Password"
                            aria-invalid={!!form.errors.password}
                        />
                        <FieldError>{form.errors.password}</FieldError>
                    </Field>

                    <div className="flex items-center space-x-3">
                        <Checkbox
                            id="remember"
                            checked={form.data.remember}
                            onCheckedChange={(checked) =>
                                form.setData('remember', checked === true)
                            }
                        />
                        <Label htmlFor="remember">Remember me</Label>
                    </div>

                    <Button
                        type="submit"
                        className="mt-4 w-full"
                        disabled={form.processing}
                    >
                        {form.processing && <Spinner />}
                        Log in
                    </Button>
                </div>

                {canRegister && (
                    <div className="text-center text-sm text-muted-foreground">
                        Don't have an account?{' '}
                        <TextLink href={registerShow.url()}>Sign up</TextLink>
                    </div>
                )}
            </form>
        </AuthLayout>
    );
}
