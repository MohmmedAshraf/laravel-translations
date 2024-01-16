<script setup lang="ts">
import { ref, defineEmits } from "vue"
import { Translation } from "../../../scripts/types"
import { POSITION, useToast } from "vue-toastification"

const props = defineProps<{
    translation: Translation
    selectedIds: number[]
}>()

const toast = useToast()

const { emit } = defineEmits()

const deleting = ref(false)

const showConfirmDeleteDialog = ref(false)

const openConfirmDeleteDialog = () => {
    showConfirmDeleteDialog.value = true
}

function deleteTranslation(id: number) {
    deleting.value = true

    router.delete(route("ltu.translation.destroy", id), {
        preserveScroll: true,
        onSuccess: () => {
            deleting.value = false
            closeConfirmDeleteDialog()
        },
        onError: () => {
            toast.error("Something went wrong, please try again.", {
                icon: true,
                position: POSITION.BOTTOM_CENTER,
            })
        },
    })
}

const closeConfirmDeleteDialog = () => {
    showConfirmDeleteDialog.value = false
}

const selected = ref(props.selectedIds.includes(props.translation.id))

watch(
    () => props.selectedIds,
    (newSelectedIds) => {
        selected.value = newSelectedIds.includes(props.translation.id)
    },
)
</script>

<template>
    <div class="flex h-14 flex-row gap-y-2 no-underline transition-colors duration-100 hover:bg-gray-50">
        <div class="relative flex flex-1 divide-x border-r no-underline">
            <div class="flex max-w-full">
                <div class="mr-0 flex w-14 items-center justify-center">
                    <div class="flex shrink-0 items-center">
                        <InputCheckbox v-model="selected" :value="translation.id" />
                    </div>
                </div>
            </div>

            <Link :href="route('ltu.phrases.index', translation.id)" class="flex w-full divide-x">
                <div class="flex flex-1 justify-between gap-x-4 truncate px-4">
                    <div class="flex w-full items-center truncate font-medium">
                        <Flag :country-code="translation.language.code" class="mr-2" />

                        <span class="mr-2 max-w-full truncate text-base text-gray-700">
                            {{ translation.language.name }}
                        </span>

                        <div class="inline-block">
                            <span class="flex h-5 items-center rounded-md border px-1.5 text-xs font-normal leading-none text-gray-600">
                                {{ translation.language.code }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="hidden flex-1 flex-wrap content-center items-center gap-1 px-4 md:flex md:max-w-72 lg:max-w-80 xl:max-w-96">
                    <div v-tooltip="`${translation.progress}` + ' strings translated'" class="w-full py-2">
                        <div class="translation-progress w-full overflow-hidden rounded-full bg-gray-200">
                            <div class="h-2 bg-green-600" :style="{ width: `${translation.progress}` }"></div>
                        </div>
                    </div>
                </div>
            </Link>
        </div>

        <div class="hidden w-full border-r sm:flex sm:w-14">
            <Link :href="route('ltu.phrases.index', translation.id)" class="relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-400 outline-none transition-colors duration-150 ease-out hover:bg-blue-50 hover:text-blue-500 focus:border-blue-50">
                <div class="visible flex h-full w-full items-center justify-center leading-none">
                    <span class="mx-auto whitespace-nowrap sm:hidden">Manage Keys</span>

                    <IconLanguage class="hidden h-5 w-5 sm:flex" />
                </div>
            </Link>
        </div>

        <div class="flex h-full">
            <div class="flex w-full max-w-full">
                <button type="button" @click="openConfirmDeleteDialog" class="relative inline-flex h-14 w-14 cursor-pointer select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out hover:bg-red-50 hover:text-red-600">
                    <IconTrash class="h-5 w-5" />
                </button>
            </div>

            <ConfirmationDialog size="sm" :show="showConfirmDeleteDialog">
                <div class="flex flex-col p-6">
                    <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                    <span class="mt-2 text-sm text-gray-500">
                        This action cannot be undone, This will permanently delete the <span class="font-medium text-gray-900">{{ translation.language.name }}</span> language and all of its translations.
                    </span>

                    <div class="mt-4 flex gap-4">
                        <BaseButton variant="secondary" type="button" size="lg" @click="closeConfirmDeleteDialog" full-width> Cancel </BaseButton>
                        <BaseButton variant="danger" type="button" size="lg" @click="deleteTranslation(translation.id)" :is-loading="deleting" full-width> Delete </BaseButton>
                    </div>
                </div>
            </ConfirmationDialog>
        </div>
    </div>
</template>
