<script setup lang="ts">
const passwordInput = ref<HTMLInputElement | null>(null)
const currentPasswordInput = ref<HTMLInputElement | null>(null)

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
})

const updatePassword = () => {
    form.put(route("ltu.profile.password.update"), {
        preserveScroll: true,

        onSuccess: () => {
            form.reset()
        },

        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation")
                passwordInput.value?.focus()
            }
            if (form.errors.current_password) {
                form.reset("current_password")
                currentPasswordInput.value?.focus()
            }
        },
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="updatePassword">
            <div class="space-y-1">
                <InputLabel for="current_password" value="Current Password" />

                <InputText id="current_password" ref="currentPasswordInput" v-model="form.current_password" :error="form.errors.current_password" type="password" autocomplete="current-password" />

                <InputError :message="form.errors.current_password" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password" value="New Password" />

                <InputText id="password" ref="passwordInput" v-model="form.password" :error="form.errors.password" type="password" autocomplete="new-password" />

                <InputError :message="form.errors.password" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <InputText id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" />

                <InputError :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
                <BaseButton type="submit" size="md" variant="primary" :is-loading="form.processing"> Save </BaseButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
