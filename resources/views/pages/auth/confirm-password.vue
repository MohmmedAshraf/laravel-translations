<script setup lang="ts">
const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('ltu.password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <LayoutGuest>
        <Head title="Confirm Password" />

        <template #title> Confirm Password </template>

        <template #subtitle>
            This is a secure area of the application. Please confirm your password before continuing.
        </template>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="password" value="Password" />

                <InputText
                    id="password"
                    v-model="form.password"
                    :error="form.errors.password"
                    type="password"
                    required
                    autocomplete="current-password"
                    autofocus
                />

                <InputError :message="form.errors.password" />
            </div>

            <BaseButton type="submit" variant="primary" :is-loading="form.processing" full-width>
                Confirm
            </BaseButton>
        </form>
    </LayoutGuest>
</template>
