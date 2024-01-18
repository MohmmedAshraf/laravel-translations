<script setup lang="ts">
import { ref } from "vue"
import { Phrase } from "../../../scripts/types"
import useConfirmationDialog from "../../../scripts/composables/use-confirmation-dialog"

const props = defineProps<{
    phrase: Phrase
    selectedIds: number[]
}>()

const { loading, showDialog, openDialog, performAction, closeDialog } = useConfirmationDialog()

const deletePhrase = async (uuid: string) => {
    await performAction(() => router.delete(route("ltu.source_translation.delete_phrase", uuid)))
}

const selected = ref(props.selectedIds.includes(props.phrase.id))

watch(
    () => props.selectedIds,
    (newSelectedIds) => {
        selected.value = newSelectedIds.includes(props.phrase.id)
    },
)
</script>
<template>
    <div class="w-full hover:bg-gray-100">
        <div class="flex h-14 w-full divide-x">
            <div class="flex w-12 items-center justify-center p-4">
                <InputCheckbox v-model="selected" :value="phrase.id" />
            </div>

            <Link :href="route('ltu.source_translation.edit', phrase.uuid)" class="grid w-full grid-cols-2 divide-x">
                <div class="flex w-full items-center justify-start px-4">
                    <div class="truncate rounded-md border bg-white px-1.5 py-0.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600">
                        {{ phrase.key }}
                    </div>
                </div>

                <div class="flex w-full items-center justify-start px-4">
                    <div class="w-full truncate whitespace-nowrap text-sm font-medium text-gray-600">
                        {{ phrase.value }}
                    </div>
                </div>
            </Link>

            <div class="grid w-36 grid-cols-2 divide-x">
                <Link v-tooltip="'Edit'" :href="route('ltu.source_translation.edit', phrase.uuid)" class="group flex items-center justify-center px-3 hover:bg-blue-50">
                    <IconPencil class="size-5 text-gray-400 group-hover:text-blue-600" />
                </Link>

                <button v-tooltip="'Delete'" type="button" class="group flex items-center justify-center px-3 hover:bg-red-50" @click="openDialog">
                    <IconTrash class="size-5 text-gray-400 group-hover:text-red-600" />
                </button>
            </div>

            <ConfirmationDialog size="sm" :show="showDialog">
                <div class="flex flex-col p-6">
                    <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                    <span class="mt-2 text-sm text-gray-500"> This action cannot be undone, This will permanently delete the selected languages and all of their translations. </span>

                    <div class="mt-4 flex gap-4">
                        <BaseButton variant="secondary" type="button" size="lg" full-width @click="closeDialog"> Cancel </BaseButton>

                        <BaseButton variant="danger" type="button" size="lg" :is-loading="loading" full-width @click="deletePhrase(phrase.uuid)"> Delete </BaseButton>
                    </div>
                </div>
            </ConfirmationDialog>
        </div>
    </div>
</template>
