<script setup lang="ts">
import InvitedTable from "./partials/invited-table.vue"
import { Contributor, Invite } from "../../../scripts/types"
import { useAuth } from "../../../scripts/composables/use-auth"
import useConfirmationDialog from "../../../scripts/composables/use-confirmation-dialog"

defineProps<{
    invited: {
        data: Record<string, Invite>
        links: Record<string, string>
        meta: Record<string, string>
    }
    contributors: {
        data: Record<string, Contributor>
        links: Record<string, string>
        meta: Record<string, string>
    }
}>()

const user = useAuth().value

const { loading, showDialog, openDialog, performAction, closeDialog } = useConfirmationDialog()

const deleteContributor = async (id: number) => {
    await performAction(() => router.delete(route("ltu.contributors.delete", id)))
}
</script>

<template>
    <Head title="Contributors" />

    <LayoutDashboard>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <tabs>
                <tab
                    name="Contributors"
                    prefix='<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.49984 9.99996C9.10817 9.99996 10.4165 8.69163 10.4165 7.08329C10.4165 5.47496 9.10817 4.16663 7.49984 4.16663C5.8915 4.16663 4.58317 5.47496 4.58317 7.08329C4.58317 8.69163 5.8915 9.99996 7.49984 9.99996ZM1.6665 14.375C1.6665 12.4333 5.54984 11.4583 7.49984 11.4583C9.44984 11.4583 13.3332 12.4333 13.3332 14.375V15.8333H1.6665V14.375ZM7.49984 13.125C6.00817 13.125 4.3165 13.6833 3.6165 14.1666H11.3832C10.6832 13.6833 8.9915 13.125 7.49984 13.125ZM8.74984 7.08329C8.74984 6.39163 8.1915 5.83329 7.49984 5.83329C6.80817 5.83329 6.24984 6.39163 6.24984 7.08329C6.24984 7.77496 6.80817 8.33329 7.49984 8.33329C8.1915 8.33329 8.74984 7.77496 8.74984 7.08329ZM13.3665 11.5083C14.3332 12.2083 14.9998 13.1416 14.9998 14.375V15.8333H18.3332V14.375C18.3332 12.6916 15.4165 11.7333 13.3665 11.5083ZM15.4165 7.08329C15.4165 8.69163 14.1082 9.99996 12.4998 9.99996C12.0498 9.99996 11.6332 9.89163 11.2498 9.70829C11.7748 8.96663 12.0832 8.05829 12.0832 7.08329C12.0832 6.10829 11.7748 5.19996 11.2498 4.45829C11.6332 4.27496 12.0498 4.16663 12.4998 4.16663C14.1082 4.16663 15.4165 5.47496 15.4165 7.08329Z" /></svg>'>
                    <div class="w-full divide-y overflow-hidden rounded-md bg-white shadow">
                        <div class="w-full shadow-md">
                            <div class="flex h-14 w-full divide-x">
                                <div class="grid w-full grid-cols-2 divide-x md:grid-cols-3">
                                    <div class="flex w-full items-center justify-start px-4">
                                        <span class="text-sm font-medium text-gray-400">Name</span>
                                    </div>

                                    <div class="flex w-full items-center justify-start px-4">
                                        <span class="text-sm font-medium text-gray-400">Email</span>
                                    </div>

                                    <div class="hidden w-full items-center justify-start px-4 md:flex">
                                        <span class="text-sm font-medium text-gray-400">Role</span>
                                    </div>
                                </div>

                                <div class="grid w-16">
                                    <Link v-tooltip="'Invite Contributor'" :href="route('ltu.contributors.invite')" href="#" class="group flex items-center justify-center px-3 hover:bg-blue-50">
                                        <IconPlus class="size-5 text-gray-400 group-hover:text-blue-600" />
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-for="contributor in contributors.data" :key="contributor.id" class="w-full hover:bg-gray-100">
                            <div class="flex h-14 w-full divide-x">
                                <div class="grid w-full grid-cols-2 divide-x md:grid-cols-3">
                                    <div class="flex w-full items-center justify-start px-4">
                                        <span class="truncate text-sm font-medium text-gray-500">
                                            {{ contributor.name }}
                                        </span>
                                    </div>

                                    <div class="flex w-full items-center justify-start px-4">
                                        <span class="truncate text-sm font-medium text-gray-500">
                                            {{ contributor.email }}
                                        </span>
                                    </div>

                                    <div class="hidden w-full items-center justify-start px-4 md:flex">
                                        <div class="truncate whitespace-nowrap text-sm font-medium text-gray-500">
                                            {{ contributor.role.label }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid w-16">
                                    <button v-if="user.id === contributor.id" v-tooltip="'You cannot delete yourself!'" disabled class="group flex cursor-not-allowed items-center justify-center bg-gray-50 px-3">
                                        <IconTrash class="size-5 text-gray-400" />
                                    </button>

                                    <button v-else v-tooltip="'Delete'" class="group flex items-center justify-center px-3 hover:bg-red-50" @click="openDialog">
                                        <IconTrash class="size-5 text-gray-400 group-hover:text-red-600" />
                                    </button>
                                </div>

                                <ConfirmationDialog size="sm" :show="showDialog">
                                    <div class="flex flex-col p-6">
                                        <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                                        <span class="mt-2 text-sm text-gray-500">
                                            This action cannot be undone, This will permanently delete the <span class="font-medium"> {{ contributor.name }} </span> invitation.
                                        </span>

                                        <div class="mt-4 flex gap-4">
                                            <BaseButton variant="secondary" type="button" size="lg" full-width @click="closeDialog"> Cancel </BaseButton>

                                            <BaseButton variant="danger" type="button" size="lg" :is-loading="loading" full-width @click="deleteContributor(contributor.id)"> Delete </BaseButton>
                                        </div>
                                    </div>
                                </ConfirmationDialog>
                            </div>
                        </div>

                        <Pagination :links="contributors.links" :meta="contributors.meta" />
                    </div>
                </tab>

                <tab name="Invited" prefix='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><g><rect style="fill:none;" width="24" height="24"/><path d="M20,4H4C2.9,4,2,4.9,2,6l0,12c0,1.1,0.9,2,2,2h8v-2H4V8l8,5l8-5v5h2V6C22,4.9,21.1,4,20,4z M12,11L4,6h16L12,11z"/></g><polygon points="22,18 22,20 19.4,20 19.4,22.6 17.4,22.6 17.4,20 14.8,20 14.8,18 17.4,18 17.4,15.4 19.4,15.4 19.4,18 "/></svg>'>
                    <InvitedTable :invitations="invited" />
                </tab>
            </tabs>
        </div>
    </LayoutDashboard>
</template>
