<script setup lang="ts">
import { computed, ref, watch } from "vue"
import { Phrase, Translation, TranslationFile } from "../../../scripts/types"
import SourcePhraseItem from "./source-phrase-item.vue"
import useConfirmationDialog from "../../../scripts/composables/use-confirmation-dialog"
import { debounce } from "lodash"

const props = defineProps<{
    phrases: {
        data: Record<string, Phrase>
        links: Record<string, string>
        meta: Record<string, string>
    }
    translation: Translation
    files: Record<string, TranslationFile>

    filter: {
        keyword?: string
        translationFile?: string
    }
}>()

const searchField = ref(props.filter?.keyword || "")
const phraseTranslationFile = ref(props.filter?.translationFile || "")

const translationFiles = computed(() => {
    return props.files.map((fileType: TranslationFile) => {
        return {
            value: fileType.id,
            label: fileType.nameWithExtension,
        }
    })
})

watch(
    [searchField, phraseTranslationFile],
    debounce(() => {
        router.get(
            route("ltu.source_translation"),
            {
                filter: {
                    keyword: searchField.value || undefined,
                    translationFile: phraseTranslationFile.value || undefined,
                },
            },
            {
                preserveState: true,
                preserveScroll: true,
                only: ["phrases"],
            },
        )
    }, 300),
)

const selectedIds = ref<number[]>([])

const { loading, showDialog, openDialog, performAction, closeDialog } = useConfirmationDialog()

const deletePhrases = async () => {
    await performAction(() =>
        router.post(
            route("ltu.source_translation.delete_phrases"),
            { selected_ids: selectedIds.value },
            {
                preserveScroll: true,
                onSuccess: () => {
                    selectedIds.value = []
                },
            },
        ),
    )
}

function toggleSelection() {
    if (selectedIds.value.length === props.phrases.data.length) {
        selectedIds.value = []
    } else {
        selectedIds.value = props.phrases.data.map((phrase: Phrase) => phrase.id)
    }
}

const isAllSelected = computed(() => selectedIds.value.length === Object.keys(props.phrases.data).length)
</script>
<template>
    <Head title="Base Language" />

    <LayoutDashboard>
        <div class="w-full bg-white shadow">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 lg:px-8">
                <div class="flex w-full items-center">
                    <div class="flex w-full items-center gap-3 py-4">
                        <Link :href="route('ltu.source_translation')" class="flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100">
                            <div class="h-5 shrink-0">
                                <Flag :country-code="translation.language.code" width="w-5" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <div class="text-sm font-semibold text-gray-600" v-text="translation.language.name"></div>
                            </div>
                        </Link>

                        <div class="rounded-md border bg-white px-1.5 py-0.5 text-sm text-gray-500" v-text="translation.language.code"></div>
                    </div>
                </div>

                <Link v-tooltip="'Go back'" :href="route('ltu.translation.index')" class="flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200">
                    <IconArrowRight class="size-6 text-gray-400" />
                </Link>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="w-full divide-y overflow-hidden rounded-md bg-white shadow">
                <div class="flex w-full flex-wrap items-center justify-between gap-4 px-4 py-3 sm:flex-nowrap">
                    <div class="w-full max-w-full md:max-w-sm">
                        <InputText v-model="searchField" placeholder="Search by key or value" size="md" />
                    </div>

                    <div class="w-full max-w-full md:max-w-sm">
                        <InputNativeSelect id="translationFile" v-model="phraseTranslationFile" size="md" placeholder="Filter by file" :items="translationFiles" />
                    </div>
                </div>

                <div class="w-full shadow-md">
                    <div class="flex h-14 w-full divide-x">
                        <div class="flex w-12 items-center justify-center p-4">
                            <InputCheckbox :disabled="!phrases.data.length" :checked="isAllSelected" @click="toggleSelection" />
                        </div>

                        <div class="grid w-full grid-cols-2 divide-x">
                            <div class="flex w-full items-center justify-start px-4">
                                <span class="text-sm font-medium text-gray-400">Key</span>
                            </div>

                            <div class="flex w-full items-center justify-start px-4">
                                <span class="text-sm font-medium text-gray-400">String</span>
                            </div>
                        </div>

                        <div class="grid w-36 grid-cols-2 divide-x">
                            <Link v-tooltip="'Add New Key'" :href="route('ltu.source_translation.add_source_key')" class="group flex items-center justify-center hover:bg-blue-50">
                                <IconPlus class="size-5 text-gray-400 group-hover:text-blue-600" />
                            </Link>

                            <div class="flex h-full">
                                <div class="flex w-full max-w-full">
                                    <button
                                        v-tooltip="selectedIds.length ? 'Delete selected' : 'Select phrases to delete'"
                                        type="button"
                                        class="relative inline-flex size-14 select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out"
                                        :disabled="!selectedIds.length"
                                        :class="{
                                            'cursor-not-allowed': !selectedIds.length,
                                            'cursor-pointer': selectedIds.length,
                                            'hover:bg-red-50 hover:text-red-600': selectedIds.length,
                                            'bg-gray-50': !selectedIds.length,
                                        }"
                                        @click="openDialog">
                                        <IconTrash class="size-5" />
                                    </button>
                                </div>
                            </div>

                            <ConfirmationDialog size="sm" :show="showDialog">
                                <div class="flex flex-col p-6">
                                    <span class="text-xl font-medium text-gray-700">Are you sure?</span>

                                    <span class="mt-2 text-sm text-gray-500"> This action cannot be undone, This will permanently delete the selected phrases and all of their translations. </span>

                                    <div class="mt-4 flex gap-4">
                                        <BaseButton variant="secondary" type="button" size="lg" full-width @click="closeDialog"> Cancel </BaseButton>

                                        <BaseButton variant="danger" type="button" size="lg" :is-loading="loading" full-width @click="deletePhrases"> Delete </BaseButton>
                                    </div>
                                </div>
                            </ConfirmationDialog>
                        </div>
                    </div>
                </div>

                <SourcePhraseItem v-for="phrase in phrases.data" :key="phrase.uuid" :phrase="phrase" :selected-ids="selectedIds" />

                <Pagination :links="phrases.links" :meta="phrases.meta" />
            </div>
        </div>
    </LayoutDashboard>
</template>
