<script setup lang="ts">
defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>()

const form = useForm({
    email: "",
    password: "",
    remember: false
})

const submit = () => {
    form.post(route("ltu.login.attempt"), {
        onFinish: () => {
            form.reset("password")
        }
    })
}
</script>

<template>
    <LayoutGuest>
        <Head title="Log in" />

        <template #title> Sign in to your account</template>

        <template #subtitle>
            Not a member?
            <Link href="#" class="font-semibold text-blue-600 hover:text-blue-500">
                Start a 14 day free trial
            </Link>
        </template>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="email" value="Email Address" />

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
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <InputCheckbox id="remember-me" v-model:checked="form.remember" name="remember" />

                    <label for="remember-me" class="block cursor-pointer text-sm leading-6 text-gray-700">
                        Remember me
                    </label>
                </div>

                <div v-if="canResetPassword" class="text-sm leading-6">
                    <Link :href="route('ltu.password.request')" class="font-semibold text-blue-600 hover:text-blue-500">
                        Forgot password?
                    </Link>
                </div>
            </div>

            <BaseButton type="submit" variant="primary" :is-loading="form.processing" full-width>
                Sign in
            </BaseButton>
        </form>

        <div class="mt-10">
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
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
