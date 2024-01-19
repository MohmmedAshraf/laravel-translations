<script setup lang="ts">
const props = defineProps<{
    email: string
    token: string
}>()

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
})

const submit = () => {
    form.post(route("ltu.password.update"), {
        onFinish: () => {
            form.reset("password", "password_confirmation")
        },
    })
}
</script>

<template>
    <LayoutGuest>
        <Head title="Reset Password" />

        <template #title> Reset Password </template>

        <template #subtitle> Ensure your new password is at least 8 characters long to ensure security. </template>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1 opacity-45">
                <InputLabel for="email" value="Email Address" class="sr-only" />

                <div class="w-full cursor-not-allowed rounded-md border border-gray-300 bg-gray-100 px-2 py-2.5 text-sm" v-text="email"></div>

                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password" value="Password" class="sr-only" />

                <InputPassword id="password" v-model="form.password" :error="form.errors.password" required autocomplete="new-password" placeholder="New Password" class="bg-gray-50" />

                <InputError :message="form.errors.password" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password_confirmation" value="Confirm Password" class="sr-only" />

                <InputPassword id="password_confirmation" v-model="form.password_confirmation" :error="form.errors.password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" class="bg-gray-50" />

                <InputError :message="form.errors.password_confirmation" />
            </div>

            <BaseButton type="submit" variant="secondary" :is-loading="form.processing" full-width> Reset Password </BaseButton>
        </form>
    </LayoutGuest>
</template>
