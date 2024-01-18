<script setup lang="ts">
import { Invite } from "../../../../scripts/types"
import useConfirmationDialog from "../../../../scripts/composables/use-confirmation-dialog"

defineProps<{
    invitation: Invite
}>()

const { loading, showDialog, openDialog, performAction, closeDialog } = useConfirmationDialog()

const deleteInvitation = async (id: number) => {
    await performAction(() => router.delete(route("ltu.contributors.invite.delete", id)))
}
</script>

<template>
    <div class="w-full hover:bg-gray-100">
        <div class="flex h-14 w-full divide-x">
            <div class="grid w-full grid-cols-3 divide-x">
                <div class="col-span-3 flex w-full items-center justify-start px-4 sm:col-span-1">
                    <span class="text-sm font-medium text-gray-500">
                        {{ invitation.email }}
                    </span>
                </div>

                <div class="hidden w-full items-center justify-start px-4 md:flex">
                    <div class="truncate whitespace-nowrap text-sm font-medium text-gray-500">
                        {{ invitation.role.label }}
                    </div>
                </div>

                <div class="hidden w-full items-center justify-start px-4 md:flex">
                    <div class="truncate whitespace-nowrap text-sm font-medium text-gray-500">
                        {{ invitation.invited_at }}
                    </div>
                </div>
            </div>

            <div class="grid w-16">
                <button v-tooltip="'Delete'" type="button" class="group flex items-center justify-center px-3 hover:bg-red-50" @click="openDialog">
                    <IconTrash class="size-5 text-gray-400 group-hover:text-red-600" />
                </button>
            </div>

            <ConfirmationDialog size="sm" :show="showDialog">
                <div class="flex flex-col p-6">
                    <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                    <span class="mt-2 text-sm text-gray-500">
                        This action cannot be undone, This will permanently delete the <span class="font-medium"> {{ invitation.email }} </span> invitation.
                    </span>

                    <div class="mt-4 flex gap-4">
                        <BaseButton variant="secondary" type="button" size="lg" full-width @click="closeDialog"> Cancel </BaseButton>

                        <BaseButton variant="danger" type="button" size="lg" :is-loading="loading" full-width @click="deleteInvitation(invitation.id)"> Delete </BaseButton>
                    </div>
                </div>
            </ConfirmationDialog>
        </div>
    </div>
</template>
