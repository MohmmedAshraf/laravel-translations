<script setup lang="ts">
import { ref, computed } from "vue"
import { UseClipboard } from "@vueuse/components"
import TranslationItem from "./translation-item.vue"
import { POSITION, useToast } from "vue-toastification"
import { SourceTranslation, Translation } from "../../../scripts/types"

const props = defineProps<{
    translations: {
        data: Translation[]
    }

    source_translation: SourceTranslation
}>()

const toast = useToast()
const searchQuery = ref("")
const selectedIds = ref<number[]>([])
const deletingSelected = ref(false)

const showConfirmDeleteSelectedDialog = ref(false)

const openConfirmDeleteSelectedDialog = () => {
    showConfirmDeleteSelectedDialog.value = true
}

const closeConfirmDeleteSelectedDialog = () => {
    showConfirmDeleteSelectedDialog.value = false
}

function deleteSelected() {
    deletingSelected.value = true

    router.post(
        route("ltu.translation.destroy.multiple"),
        { selected_ids: selectedIds.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                selectedIds.value = []
                deletingSelected.value = false
                closeConfirmDeleteSelectedDialog()
            },
            onError: () => {
                toast.error("Something went wrong, please try again.", {
                    icon: true,
                    position: POSITION.BOTTOM_CENTER,
                })
            },
        },
    )
}

const filteredTranslations = computed(() => {
    return props.translations.data.filter((language) => {
        return language.language.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || language.language.code.toLowerCase().includes(searchQuery.value.toLowerCase())
    })
})

function toggleSelection() {
    if (selectedIds.value.length === props.translations.data.length) {
        selectedIds.value = []
    } else {
        selectedIds.value = props.translations.data.map((language: Translation) => language.id)
    }
}

const isAllSelected = computed(() => selectedIds.value.length === filteredTranslations.length)
</script>

<template>
    <Head title="Translations" />

    <LayoutDashboard>
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div v-if="source_translation" class="w-full filter">
                <div class="w-auto">
                    <InputText v-model="searchQuery" placeholder="Search languages by name or code" />
                </div>
            </div>

            <div v-if="source_translation" class="mt-4 overflow-hidden rounded-lg border bg-white shadow">
                <div class="relative z-10 flex w-full divide-x shadow">
                    <div class="flex w-14 shrink-0 items-center justify-center">
                        <div class="flex shrink-0 items-center">
                            <InputCheckbox :disabled="!translations.data.length" @click="toggleSelection" :checked="isAllSelected" />
                        </div>
                    </div>

                    <div class="hidden flex-1 items-center px-4 text-sm text-gray-500 sm:flex">Language name</div>

                    <div class="hidden flex-1 items-center px-4 text-sm text-gray-500 md:flex md:max-w-72 lg:max-w-80 xl:max-w-96">Translation progress</div>

                    <div class="w-full sm:w-14">
                        <Link :href="route('ltu.translation.create')" class="relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-700 outline-none transition-colors duration-150 ease-out hover:bg-blue-50 hover:text-blue-500 focus:border-blue-50 sm:text-gray-400">
                            <div class="visible flex h-full w-full items-center justify-center leading-none">
                                <span class="mx-auto whitespace-nowrap sm:hidden">Add Language</span>

                                <IconPlus class="hidden h-5 w-5 fill-current sm:flex" />
                            </div>
                        </Link>
                    </div>

                    <div class="flex h-full">
                        <div class="flex w-full max-w-full">
                            <button
                                type="button"
                                class="relative inline-flex h-14 w-14 select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out"
                                @click="openConfirmDeleteSelectedDialog"
                                :disabled="!selectedIds.length"
                                :class="{
                                    'cursor-not-allowed': !selectedIds.length,
                                    'cursor-pointer': selectedIds.length,
                                    'hover:bg-red-50 hover:text-red-600': selectedIds.length,
                                    'bg-gray-50': !selectedIds.length,
                                }">
                                <IconTrash class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <ConfirmationDialog size="sm" :show="showConfirmDeleteSelectedDialog">
                        <div class="flex flex-col p-6">
                            <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                            <span class="mt-2 text-sm text-gray-500"> This action cannot be undone, This will permanently delete the selected languages and all of their translations. </span>

                            <div class="mt-4 flex gap-4">
                                <BaseButton variant="secondary" type="button" size="lg" @click="closeConfirmDeleteSelectedDialog" full-width> Cancel </BaseButton>
                                <BaseButton variant="danger" type="button" size="lg" @click="deleteSelected()" :is-loading="deletingSelected" full-width> Delete </BaseButton>
                            </div>
                        </div>
                    </ConfirmationDialog>
                </div>

                <div class="divide-y">
                    <div class="flex h-14 flex-row gap-y-2 no-underline transition-colors duration-100 hover:bg-gray-50">
                        <div class="flex flex-1 divide-x border-r no-underline">
                            <div class="flex max-w-full">
                                <div class="mr-0 flex w-14 items-center justify-center bg-gray-50">
                                    <div class="flex shrink-0 items-center opacity-50">
                                        <InputCheckbox disabled />
                                    </div>
                                </div>
                            </div>

                            <Link :href="route('ltu.source_translation')" class="flex w-full divide-x">
                                <div class="flex flex-1 justify-between gap-x-4 truncate px-4">
                                    <div class="flex w-full items-center truncate font-medium">
                                        <Flag :country-code="source_translation.language.code" class="mr-2" />

                                        <span class="mr-2 max-w-full truncate text-base text-gray-700">
                                            {{ source_translation.language.name }}
                                        </span>

                                        <div class="inline-block">
                                            <span class="flex h-5 items-center rounded-md border px-1.5 text-xs font-normal leading-none text-gray-600">
                                                {{ source_translation.language.code }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="hidden flex-1 flex-wrap content-center items-center gap-1 px-4 md:flex md:max-w-72 lg:max-w-80 xl:max-w-96">
                                    <span class="mr-1 shrink-0 text-sm leading-tight text-gray-600">{{ source_translation.phrases_count }} source keys</span>
                                </div>
                            </Link>
                        </div>

                        <Link :href="route('ltu.source_translation')" class="group hidden w-full border-r sm:flex sm:w-14">
                            <div class="relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-700 outline-none transition-colors duration-150 ease-out focus:border-blue-50 group-hover:bg-blue-50 group-hover:text-blue-500">
                                <div class="visible flex h-full w-full items-center justify-center leading-none">
                                    <span class="mx-auto whitespace-nowrap sm:hidden">Manage Keys</span>

                                    <IconCog class="hidden h-5 w-5 fill-current text-gray-400 group-hover:text-blue-500 sm:flex" />
                                </div>
                            </div>
                        </Link>

                        <div class="flex h-full">
                            <div class="flex w-full max-w-full">
                                <div class="relative inline-flex h-14 w-14 cursor-not-allowed select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out">
                                    <IconTrash class="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="filteredTranslations.length">
                        <TranslationItem v-for="translation in filteredTranslations" :translation="translation" :selected-ids="selectedIds" :key="translation.language.code" />
                    </template>

                    <Link v-if="filteredTranslations.length" :href="route('ltu.translation.create')" class="flex cursor-pointer items-center justify-between rounded-b border-t p-4 text-gray-500 transition-colors duration-100 hover:bg-blue-50 hover:text-blue-600">
                        <div class="text-sm font-medium uppercase tracking-wide">Add new language</div>

                        <IconPlus class="h-6 w-6" />
                    </Link>

                    <EmptyState v-else class="bg-gray-50 py-32" title="There are no languages to translate your project to." description="Add the first one and start translating.">
                        <template #icon>
                            <IconEmptyTranslations class="w-20 text-gray-400" />
                        </template>

                        <Link :href="route('ltu.translation.create')" class="btn btn-primary btn-md mt-4">
                            <div class="visible flex items-center leading-none">
                                <IconPlus class="mr-1 h-5 w-5 fill-white" />

                                <span class="whitespace-nowrap">Add languages</span>
                            </div>
                        </Link>
                    </EmptyState>
                </div>
            </div>

            <EmptyState v-else class="mt-4 min-h-[calc(100vh-11rem)] rounded-md border bg-white py-12 shadow" title="Source language has not been imported yet." description="To import the source translation, run the following command in your terminal.">
                <template #icon>
                    <IconEmptyTranslations class="w-20 text-gray-400" />
                </template>

                <code class="mt-4 flex items-center rounded-md border">
                    <span class="px-2 py-1 text-sm font-medium text-gray-500">php artisan translation:import</span>

                    <UseClipboard v-slot="{ copy, copied }" source="php artisan translation:import">
                        <button type="button" @click="copy()" v-tooltip="copied ? 'Copied' : 'Copy'" class="h-full border-l p-2 text-gray-400 hover:bg-blue-50 hover:text-blue-500">
                            <IconClipboard class="h-4 w-4" />
                        </button>
                    </UseClipboard>
                </code>
            </EmptyState>
        </div>
    </LayoutDashboard>
</template>
