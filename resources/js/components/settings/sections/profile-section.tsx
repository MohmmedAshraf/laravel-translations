import { Transition } from '@headlessui/react';
import { Form } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { update } from '@/routes/ltu/account';
import type { User } from '@/types';

interface ProfileSectionProps {
    user: User;
}

export function ProfileSection({ user }: ProfileSectionProps) {
    return (
        <Form
            action={update().url}
            method="put"
            options={{ preserveScroll: true }}
            className="flex h-full flex-col"
        >
            {({ processing, recentlySuccessful, errors }) => (
                <>
                    <div className="flex-1 overflow-y-auto p-4 md:p-6">
                        <div className="pb-5">
                            <h3 className="text-base font-semibold">Profile</h3>
                            <p className="text-sm text-muted-foreground">
                                Update your name and email address
                            </p>
                        </div>

                        <div className="border-t border-border" />

                        <div className="space-y-5 py-5">
                            {/* Name */}
                            <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div className="md:flex-1 md:pr-8">
                                    <p className="text-sm font-medium">Name</p>
                                    <p className="text-sm text-muted-foreground">
                                        Your display name
                                    </p>
                                </div>
                                <div className="w-full md:w-[280px]">
                                    <Field data-invalid={!!errors.name}>
                                        <FieldLabel
                                            htmlFor="name"
                                            className="sr-only"
                                        >
                                            Name
                                        </FieldLabel>
                                        <Input
                                            id="name"
                                            defaultValue={user.name}
                                            name="name"
                                            required
                                            autoComplete="name"
                                            placeholder="Full name"
                                            aria-invalid={!!errors.name}
                                        />
                                        <FieldError>{errors.name}</FieldError>
                                    </Field>
                                </div>
                            </div>

                            <div className="border-t border-border" />

                            {/* Email */}
                            <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div className="md:flex-1 md:pr-8">
                                    <p className="text-sm font-medium">Email</p>
                                    <p className="text-sm text-muted-foreground">
                                        Your email address
                                    </p>
                                </div>
                                <div className="w-full md:w-[280px]">
                                    <Field data-invalid={!!errors.email}>
                                        <FieldLabel
                                            htmlFor="email"
                                            className="sr-only"
                                        >
                                            Email
                                        </FieldLabel>
                                        <Input
                                            id="email"
                                            type="email"
                                            defaultValue={user.email}
                                            name="email"
                                            required
                                            autoComplete="username"
                                            placeholder="Email address"
                                            aria-invalid={!!errors.email}
                                        />
                                        <FieldError>{errors.email}</FieldError>
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
                                Save changes
                            </Button>
                        </div>
                    </div>
                </>
            )}
        </Form>
    );
}
