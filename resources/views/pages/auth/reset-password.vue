<script setup lang="ts">
const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('ltu.password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <LayoutGuest>
        <Head title="Reset Password" />

        <template #title> Reset Password </template>

        <template #subtitle> Ensure your new password is at least 8 characters long to ensure security. </template>

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
                Reset Password
            </BaseButton>
        </form>
    </LayoutGuest>
</template>
