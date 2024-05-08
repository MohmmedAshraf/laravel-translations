<script setup
    lang="ts">
    import { trans } from 'laravel-vue-i18n'

    defineProps<{
        status?: string
    }>()

    const form = useForm({
        email: "",
    })

    const submit = () => {
        form.post(route("ltu.password.email"), {
            onFinish: () => {
                form.reset()
            },
        })
    }
</script>

<template>
    <LayoutGuest>

        <Head :title="trans('Reset your password')" />

        <template #title> {{ trans('Reset your password') }} </template>

        <template #subtitle>
            {{ trans("Enter your email address below, and we'll send you instructions on how to reset your password.")
            }}
        </template>

        <Alert v-if="status" variant="success" class="mb-4 w-full">
            {{ status }}
        </Alert>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="space-y-1">
                <InputLabel for="email" :value="trans('Email')" class="sr-only" />

                <InputText id="email" v-model="form.email" :error="form.errors.email" type="email" required autofocus
                    autocomplete="username" :placeholder="trans('Email address')" class="bg-gray-50" />

                <InputError :message="form.errors.email" />
            </div>

            <BaseButton type="submit" variant="secondary" :is-loading="form.processing" full-width>
                {{ trans('Send reset instructions') }}
            </BaseButton>
        </form>

        <div class="mt-8 flex w-full justify-center">
            <Link :href="route('ltu.login')" class="text-xs font-medium text-gray-500 hover:text-blue-500">
            {{ trans('Go back to sign in') }}
            </Link>
        </div>
    </LayoutGuest>
</template>
