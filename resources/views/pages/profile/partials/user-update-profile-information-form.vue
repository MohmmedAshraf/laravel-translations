<script setup lang="ts">
defineProps<{
    mustVerifyEmail?: Boolean
    status?: String
}>()

const user = useAuth().value

const form = useForm({
    name: user.name,
    email: user.email,
})

const updateProfileInformation = () => {
    form.patch(route("ltu.profile.update"), {
        preserveScroll: true,
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="updateProfileInformation">
            <div class="space-y-1">
                <InputLabel for="name" value="Name" />

                <InputText id="name" v-model="form.name" :error="form.errors.name" type="text" required autofocus autocomplete="name" />

                <InputError :message="form.errors.name" />
            </div>

            <div class="space-y-1">
                <InputLabel for="email" value="Email" />

                <InputText id="email" v-model="form.email" :error="form.errors.email" type="email" autocomplete="username" />

                <InputError :message="form.errors.email" />
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
