<script setup lang="ts">
import _, { debounce } from "lodash"
import { ref, watch, watchEffect } from "vue"
import PhraseItem from "./phrase-item.vue"
import { Phrase, Translation, TranslationFile } from "../../../scripts/types"

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
        status?: string
        translationFile?: string
    }
}>()

const searchField = ref(props.filter?.keyword || "")
const phraseStatus = ref(props.filter?.status || "")
const phraseTranslationFile = ref(props.filter?.translationFile || "")

const statusEnum = [
    { label: "Translated", value: "translated" },
    { label: "Untranslated", value: "untranslated" },
]

const translationFiles = computed(() => {
    return props.files.map((fileType: TranslationFile) => {
        return {
            value: fileType.id,
            label: fileType.nameWithExtension,
        }
    })
})

watch(
    [searchField, phraseStatus, phraseTranslationFile],
    debounce(() => {
        router.get(
            route("ltu.phrases.index", {
                translation: props.translation.id,
            }),
            {
                filter: {
                    keyword: searchField.value || undefined,
                    status: phraseStatus.value || undefined,
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
</script>
<template>
    <Head title="Phrases" />

    <LayoutDashboard>
        <div class="w-full bg-white shadow">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 lg:px-8">
                <div class="flex w-full items-center">
                    <div class="flex w-full items-center gap-3 py-4">
                        <Link :href="route('ltu.phrases.index', translation.id)" class="flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100">
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

                    <div class="w-full max-w-full md:max-w-sm">
                        <InputNativeSelect id="status" v-model="phraseStatus" size="md" placeholder="Filter by status" :items="statusEnum" />
                    </div>
                </div>

                <div class="w-full shadow-md">
                    <div class="flex h-14 w-full divide-x">
                        <div class="hidden w-20 items-center justify-center px-4 md:flex">
                            <span class="text-sm font-medium text-gray-400">State</span>
                        </div>

                        <div class="grid w-full grid-cols-2 divide-x md:grid-cols-3">
                            <div class="flex w-full items-center justify-start px-4">
                                <span class="text-sm font-medium text-gray-400">Key</span>
                            </div>

                            <div class="hidden w-full items-center justify-start px-4 md:flex">
                                <span class="text-sm font-medium text-gray-400">Source</span>
                            </div>

                            <div class="flex w-full items-center justify-start px-4">
                                <span class="text-sm font-medium text-gray-400"> Translation </span>
                            </div>
                        </div>

                        <div class="grid w-[67px] grid-cols-1 divide-x">
                            <Link v-tooltip="'Add New Key'" :href="route('ltu.source_translation.add_source_key')" class="group flex items-center justify-center hover:bg-blue-50">
                                <IconPlus class="size-5 text-gray-400 group-hover:text-blue-600" />
                            </Link>
                        </div>
                    </div>
                </div>

                <PhraseItem v-for="phrase in phrases.data" :key="phrase.uuid" :phrase="phrase" :translation="translation" />

                <Pagination :links="phrases.links" :meta="phrases.meta" />
            </div>
        </div>
    </LayoutDashboard>
</template>
