<script setup lang="ts">
const props = defineProps<{
    email: string
    token: string
}>()

const form = useForm({
    name: "",
    password: "",
    email: props.email,
    token: props.token,
    password_confirmation: "",
})

const submit = () => {
    form.post(route("ltu.invitation.accept.store"), {
        onFinish: () => {
            form.reset("password", "password_confirmation")
        },
    })
}
</script>

<template>
    <LayoutGuest>
        <Head title="Accept Invitation" />

        <template #title> Accept Invitation </template>

        <template #subtitle> You have been invited to join the team! </template>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="name" value="Name" class="sr-only" />

                <InputText id="name" v-model="form.name" :error="form.errors.name" type="text" required autofocus autocomplete="name" placeholder="Enter your name" class="bg-gray-50" />

                <InputError :message="form.errors.name" />
            </div>

            <div class="space-y-1">
                <InputLabel for="email" value="Email Address" class="sr-only" />

                <div class="w-full cursor-not-allowed rounded-md border border-gray-300 bg-gray-100 px-2 py-2.5 text-sm" v-text="email"></div>

                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password" value="Password" />

                <InputPassword id="password" v-model="form.password" :error="form.errors.password" required autocomplete="new-password" class="bg-gray-50" />

                <InputError :message="form.errors.password" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <InputPassword id="password_confirmation" v-model="form.password_confirmation" :error="form.errors.password_confirmation" required autocomplete="new-password" class="bg-gray-50" />

                <InputError :message="form.errors.password_confirmation" />
            </div>

            <BaseButton type="submit" variant="secondary" :is-loading="form.processing" full-width> Continue </BaseButton>
        </form>

        <div class="mt-8 flex w-full justify-center">
            <Link :href="route('ltu.login')" class="text-xs font-medium text-gray-500 hover:text-blue-500"> Go back to sign in </Link>
        </div>
    </LayoutGuest>
</template>
