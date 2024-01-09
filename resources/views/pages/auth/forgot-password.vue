<script setup lang="ts">
defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('ltu.password.email'));
};
</script>

<template>
    <LayoutGuest>
        <Head title="Forgot Password" />

        <template #title> Forgot Password </template>

        <template #subtitle>
            Enter your email address below, and we'll send you instructions on how to reset your password.
        </template>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="email" value="Email" />

                <InputText
                    id="email"
                    v-model="form.email"
                    :error="form.errors.email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError :message="form.errors.email" />
            </div>

            <BaseButton type="submit" variant="primary" :is-loading="form.processing" full-width>
                Send Instructions
            </BaseButton>
        </form>

        <div class="mt-10">
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-200" />
                </div>

                <div class="relative flex justify-center text-sm font-medium leading-6">
                    <span class="bg-white px-6 text-gray-900">Or</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4">
                <Link :href="route('ltu.login')" class="btn btn-lg border border-slate-200 text-gray-700 transition duration-150 hover:border-gray-400 hover:text-gray-900 hover:shadow">
                    <span>Go back to sign in</span>
                </Link>
            </div>
        </div>
    </LayoutGuest>
</template>
