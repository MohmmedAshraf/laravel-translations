import { Transition } from '@headlessui/react';
import { Form } from '@inertiajs/react';
import { useRef } from 'react';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { password } from '@/routes/ltu/account';

export function PasswordSection() {
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    return (
        <Form
            action={password().url}
            method="put"
            options={{ preserveScroll: true }}
            resetOnError={[
                'password',
                'password_confirmation',
                'current_password',
            ]}
            resetOnSuccess
            onError={(errors) => {
                if (errors.password) {
                    passwordInput.current?.focus();
                }
                if (errors.current_password) {
                    currentPasswordInput.current?.focus();
                }
            }}
            className="flex h-full flex-col"
        >
            {({ errors, processing, recentlySuccessful }) => (
                <>
                    <div className="flex-1 overflow-y-auto p-4 md:p-6">
                        <div className="pb-5">
                            <h3 className="text-base font-semibold">
                                Password
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                Ensure your account is using a strong password
                            </p>
                        </div>

                        <div className="border-t border-border" />

                        <div className="space-y-5 py-5">
                            {/* Current Password */}
                            <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div className="md:flex-1 md:pr-8">
                                    <p className="text-sm font-medium">
                                        Current Password
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        Enter your current password
                                    </p>
                                </div>
                                <div className="w-full md:w-[280px]">
                                    <Field
                                        data-invalid={!!errors.current_password}
                                    >
                                        <FieldLabel
                                            htmlFor="current_password"
                                            className="sr-only"
                                        >
                                            Current Password
                                        </FieldLabel>
                                        <Input
                                            id="current_password"
                                            ref={currentPasswordInput}
                                            name="current_password"
                                            type="password"
                                            autoComplete="current-password"
                                            placeholder="Current password"
                                            aria-invalid={
                                                !!errors.current_password
                                            }
                                        />
                                        <FieldError>
                                            {errors.current_password}
                                        </FieldError>
                                    </Field>
                                </div>
                            </div>

                            <div className="border-t border-border" />

                            {/* New Password */}
                            <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div className="md:flex-1 md:pr-8">
                                    <p className="text-sm font-medium">
                                        New Password
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        Choose a strong password
                                    </p>
                                </div>
                                <div className="w-full md:w-[280px]">
                                    <Field data-invalid={!!errors.password}>
                                        <FieldLabel
                                            htmlFor="password"
                                            className="sr-only"
                                        >
                                            New Password
                                        </FieldLabel>
                                        <Input
                                            id="password"
                                            ref={passwordInput}
                                            name="password"
                                            type="password"
                                            autoComplete="new-password"
                                            placeholder="New password"
                                            aria-invalid={!!errors.password}
                                        />
                                        <FieldError>
                                            {errors.password}
                                        </FieldError>
                                    </Field>
                                </div>
                            </div>

                            <div className="border-t border-border" />

                            {/* Confirm Password */}
                            <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div className="md:flex-1 md:pr-8">
                                    <p className="text-sm font-medium">
                                        Confirm Password
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        Re-enter your new password
                                    </p>
                                </div>
                                <div className="w-full md:w-[280px]">
                                    <Field
                                        data-invalid={
                                            !!errors.password_confirmation
                                        }
                                    >
                                        <FieldLabel
                                            htmlFor="password_confirmation"
                                            className="sr-only"
                                        >
                                            Confirm Password
                                        </FieldLabel>
                                        <Input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            autoComplete="new-password"
                                            placeholder="Confirm password"
                                            aria-invalid={
                                                !!errors.password_confirmation
                                            }
                                        />
                                        <FieldError>
                                            {errors.password_confirmation}
                                        </FieldError>
                                    </Field>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Fixed Footer */}
                    <div className="sticky bottom-0 border-t border-border bg-background px-4 py-4 md:px-6">
                        <div className="flex items-center justify-end gap-3">
                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-green-600">Saved</p>
                            </Transition>
                            <Button disabled={processing} size="sm">
                                Update password
                            </Button>
                        </div>
                    </div>
                </>
            )}
        </Form>
    );
}
