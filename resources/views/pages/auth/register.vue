<script setup lang="ts">
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('ltu.register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <LayoutGuest>
        <Head title="Create an account" />

        <template #title> Create an account </template>

        <template #subtitle>
            Already have an account?
            <Link :href="route('ltu.login')" class="font-semibold text-blue-600 hover:text-blue-500">
                Sign in
            </Link>
        </template>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="name" value="Name" />

                <InputText
                    id="name"
                    v-model="form.name"
                    :error="form.errors.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError :message="form.errors.name" />
            </div>

            <div class="space-y-1">
                <InputLabel for="email" value="Email Address" />

                <InputText
                    id="email"
                    v-model="form.email"
                    :error="form.errors.email"
                    type="email"
                    required
                    autocomplete="username"
                />

                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password" value="Password" />

                <InputText
                    id="password"
                    v-model="form.password"
                    :error="form.errors.password"
                    type="password"
                    required
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <InputText
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    :error="form.errors.password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" />
            </div>

            <BaseButton type="submit" variant="primary" :is-loading="form.processing" full-width>
                Create account
            </BaseButton>
        </form>

        <div class="mt-10">
            <div class="relative">
                <div
                    class="absolute inset-0 flex items-center"
                    aria-hidden="true">
                    <div class="w-full border-t border-gray-200" />
                </div>

                <div class="relative flex justify-center text-sm font-medium leading-6">
                    <span class="bg-white px-6 text-gray-900">Or continue with</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4">
                <Link href="#" class="btn btn-lg border border-slate-200 text-gray-700 transition duration-150 hover:border-gray-400 hover:text-gray-900 hover:shadow">
                    <IconGoogle class="h-5 w-5" />

                    <span>Continue with Google</span>
                </Link>
            </div>
        </div>
    </LayoutGuest>
</template>
