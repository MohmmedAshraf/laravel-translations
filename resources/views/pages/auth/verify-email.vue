<script setup lang="ts">
const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('ltu.verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <LayoutGuest>
        <Head title="Email Verification" />

        <template #title> Email Verification </template>

        <template #subtitle>
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
            we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </template>

        <div v-if="verificationLinkSent" class="mb-4 text-sm font-medium text-green-600">
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form class="mt-6" @submit.prevent="submit">
            <div class="flex flex-wrap items-center justify-between gap-6 sm:flex-nowrap">
                <BaseButton type="submit" size="md" variant="primary" :is-loading="form.processing">
                    Resend Verification Email
                </BaseButton>

                <Link :href="route('ltu.logout')" method="post" as="button" class="btn btn-lg btn-secondary">
                    Log Out
                </Link>
            </div>
        </form>
    </LayoutGuest>
</template>
