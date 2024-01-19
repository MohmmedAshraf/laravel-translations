<script setup lang="ts">
import UserUpdatePasswordForm from "@/views/pages/profile/partials/user-update-password-form.vue"
import UserUpdateProfileInformationForm from "@/views/pages/profile/partials/user-update-profile-information-form.vue"

defineProps<{
    mustVerifyEmail?: boolean
    status?: string
}>()

const pageTitle = "Profile Settings"
</script>

<template>
    <Head :title="pageTitle" />

    <LayoutDashboard :page-title="pageTitle">
        <div class="mx-auto max-w-2xl space-y-8 py-10 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <UserUpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <UserUpdatePasswordForm />
            </div>
        </div>
    </LayoutDashboard>
</template>
