<script setup lang="ts">
import { ref } from "vue"
import { Translation } from "../../../scripts/types"
import useConfirmationDialog from "../../../scripts/composables/use-confirmation-dialog"

const props = defineProps<{
    translation: Translation
    selectedIds: number[]
}>()

const { loading, showDialog, openDialog, performAction, closeDialog } = useConfirmationDialog()

const deleteTranslation = async (id: number) => {
    await performAction(() => router.delete(route("ltu.translation.delete", id)))
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
            <Link v-tooltip="'Translate'" :href="route('ltu.phrases.index', translation.id)" class="relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-400 outline-none transition-colors duration-150 ease-out hover:bg-blue-50 hover:text-blue-500 focus:border-blue-50">
                <IconLanguage class="hidden size-5 sm:flex" />
            </Link>
        </div>

        <div class="flex h-full">
            <div v-tooltip="'Delete'" class="flex w-full max-w-full">
                <button type="button" class="relative inline-flex size-14 cursor-pointer select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out hover:bg-red-50 hover:text-red-600" @click="openDialog">
                    <IconTrash class="size-5" />
                </button>
            </div>

            <ConfirmationDialog size="sm" :show="showDialog">
                <div class="flex flex-col p-6">
                    <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                    <span class="mt-2 text-sm text-gray-500">
                        This action cannot be undone, This will permanently delete the <span class="font-medium text-gray-900">{{ translation.language.name }}</span> language and all of its translations.
                    </span>

                    <div class="mt-4 flex gap-4">
                        <BaseButton variant="secondary" type="button" size="lg" full-width @click="closeDialog"> Cancel </BaseButton>

                        <BaseButton variant="danger" type="button" size="lg" :is-loading="loading" full-width @click="deleteTranslation(translation.id)"> Delete </BaseButton>
                    </div>
                </div>
            </ConfirmationDialog>
        </div>
    </div>
</template>
