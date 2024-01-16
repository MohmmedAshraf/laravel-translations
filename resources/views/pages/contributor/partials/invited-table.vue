<script setup lang="ts">
import InvitedItem from "./invited-item.vue"
import { Invite } from "../../../../scripts/types"

defineProps<{
    invitations: Invite
}>()
</script>

<template>
    <div class="w-full divide-y overflow-hidden rounded-md bg-white shadow">
        <div class="w-full shadow-md">
            <div class="flex h-14 w-full divide-x">
                <div class="grid w-full grid-cols-3 divide-x">
                    <div class="col-span-3 flex w-full items-center justify-start px-4 sm:col-span-1">
                        <span class="text-sm font-medium text-gray-400">Email</span>
                    </div>

                    <div class="hidden w-full items-center justify-start px-4 md:flex">
                        <span class="text-sm font-medium text-gray-400">Role</span>
                    </div>

                    <div class="hidden w-full items-center justify-start px-4 md:flex">
                        <span class="text-sm font-medium text-gray-400">Invitation Date</span>
                    </div>
                </div>

                <div class="grid w-16">
                    <Link :href="route('ltu.contributors.invite')" v-tooltip="'Invite Contributor'" href="#" class="group flex items-center justify-center px-3 hover:bg-blue-50">
                        <IconPlus class="h-5 w-5 text-gray-400 group-hover:text-blue-600" />
                    </Link>
                </div>
            </div>
        </div>

        <InvitedItem v-if="invitations.data.length" v-for="invitation in invitations.data" :invitation="invitation" :key="invitation.id" />

        <div v-else class="flex w-full items-center justify-center bg-gray-50 px-4 py-12">
            <span class="text-sm text-gray-400">There are no invitations yet..</span>
        </div>

        <Pagination :links="invitations.links" :meta="invitations.meta" />
    </div>
</template>
