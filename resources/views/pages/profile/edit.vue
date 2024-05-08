<script setup lang="ts">
import { Locale } from "../../../../scripts/types"
import UserUpdatePasswordForm from "@/views/pages/profile/partials/user-update-password-form.vue"
import UserUpdateProfileInformationForm from "@/views/pages/profile/partials/user-update-profile-information-form.vue"
import UserUpdateLanguageForm from "@/views/pages/profile/partials/user-update-language-form.vue"
import { trans } from 'laravel-vue-i18n'

defineProps<{
    mustVerifyEmail?: boolean
    status?: string
    locales: Locale
    localeSelected: Locale
}>()

const pageTitle = "Profile Settings"
</script>

<template>

    <Head :title="trans(pageTitle)" />

    <LayoutDashboard :page-title="trans(pageTitle)">
        <div class="mx-auto max-w-2xl space-y-8 py-10 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <UserUpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <UserUpdatePasswordForm />
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <UserUpdateLanguageForm :locales="locales" :localeSelected="localeSelected" />
            </div>
        </div>
    </LayoutDashboard>
</template>
