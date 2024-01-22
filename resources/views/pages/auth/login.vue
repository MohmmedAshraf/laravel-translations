<script setup lang="ts">
defineProps<{
    status?: string
}>()

const form = useForm({
    email: "",
    password: "",
    remember: false,
})

const submit = () => {
    form.post(route("ltu.login.attempt"), {
        onFinish: () => {
            form.reset("password")
        },
    })
}
</script>

<template>
    <LayoutGuest>
        <Head title="Log in" />

        <template #title> Sign in to your account</template>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="email" value="Email Address" class="sr-only" />

                <InputText id="email" v-model="form.email" :error="form.errors.email" type="email" required autofocus placeholder="Email address" autocomplete="username" class="bg-gray-50" />

                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password" value="Password" class="sr-only" />

                <InputPassword id="password" v-model="form.password" :error="form.errors.password" required autocomplete="current-password" placeholder="Password" class="bg-gray-50" />

                <InputError :message="form.errors.password" />
            </div>

            <BaseButton type="submit" size="lg" variant="secondary" :is-loading="form.processing" full-width> Continue </BaseButton>
        </form>

        <div class="mt-8 flex w-full justify-center">
            <Link :href="route('ltu.password.request')" class="text-xs font-medium text-gray-500 hover:text-blue-500"> Forgot your password? </Link>
        </div>
    </LayoutGuest>
</template>
