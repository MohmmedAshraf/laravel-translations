<script setup lang="ts">
import { ref, computed } from "vue"
import { XCircleIcon } from "@heroicons/vue/20/solid"
import { SourcePhrase, Translation, SourceTranslation, TranslationFile } from "../../../scripts/types"

const props = defineProps<{
    phrase: SourcePhrase
    files: TranslationFile
    translation: Translation
    source: SourceTranslation
    similarPhrases: SourcePhrase
}>()

const form = useForm({
    note: props.phrase.note || ref(""),
    phrase: props.phrase.value || ref(""),
    file: props.phrase.translation_file_id || ref(""),
})

const submit = () => {
    form.post(route("ltu.source_translation.update", props.phrase.uuid), {
        onSuccess: () => {
            form.reset()
        },
    })
}

const translationFiles = computed(() => {
    return props.files.data.map((fileType) => {
        return {
            value: fileType.id,
            label: fileType.nameWithExtension,
        };
    });
});
</script>
<template>
    <Head title="Translate" />

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

                        <div>
                            <IconArrowRight class="h-6 w-6 text-gray-400" />
                        </div>

                        <div class="flex items-center gap-2 rounded-md border border-blue-100 bg-blue-50 px-2 py-1 text-blue-500">
                            <IconKey class="h-4 w-4" />

                            <span class="text-sm" v-text="phrase.key"></span>
                        </div>
                    </div>
                </div>

                <Link v-tooltip="'Go back'" :href="route('ltu.source_translation')" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200">
                    <IconArrowRight class="h-6 w-6 text-gray-400" />
                </Link>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex w-full flex-col gap-8 lg:flex-row">
                <div class="relative w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100 focus-within:ring-blue-400">
                    <div class="flex items-center justify-between border-b px-4">
                        <div class="flex gap-2 py-2.5">
                            <div class="h-5 shrink-0">
                                <flag :country-code="translation.language.code" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <div class="text-sm font-semibold text-gray-800" v-text="translation.language.name"></div>

                                <div class="rounded-md border px-1.5 py-0.5 text-xs text-gray-500" v-text="translation.language.code"></div>
                            </div>
                        </div>

                        <div v-if="form.errors.phrase" class="rounded-md border border-red-400 bg-red-50 px-3 py-1">
                            <div class="flex items-center gap-1">
                                <div class="shrink-0">
                                    <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                                </div>

                                <div class="text-sm text-red-700">
                                    {{ form.errors.phrase }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex h-[calc(100%-80px)]">
                        <input-textarea id="textArea" v-model="form.phrase" rows="7" autofocus class="h-full w-full resize-none rounded-none border-none px-4 py-2.5 shadow-none ring-transparent focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0" />
                    </div>

                    <div class="grid grid-cols-2 border-t border-blue-200">
                        <span class="px-4 py-2.5 text-xs text-gray-400">Characters Length: {{ form.phrase.length }}</span>
                    </div>
                </div>

                <div class="w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100">
                    <div class="flex min-h-[231px] flex-col p-4">
                        <div class="w-full space-y-1">
                            <InputLabel for="file" value="File" />

                            <InputNativeSelect size="md" id="file" v-model="form.file" :error="form.errors.file" :items="translationFiles" />

                            <InputError :message="form.errors.file" />
                        </div>

                        <div class="mt-4 w-full space-y-1">
                            <InputLabel for="note" value="Translation note" />

                            <InputTextarea size="md" id="note" rows="3" class="resize-none" v-model="form.note" :error="form.errors.note" placeholder="Add a note to this translation" />

                            <InputError :message="form.errors.note" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-t border-blue-200">
                        <Link :href="route('ltu.source_translation')" class="flex items-center justify-center bg-blue-50 py-4 text-sm font-medium uppercase text-blue-600 hover:bg-blue-100"> Cancel </Link>

                        <BaseButton :is-loading="form.processing" variant="primary" class="items-center rounded-none py-4 !text-sm !font-medium uppercase" @click="submit">
                            <span class="flex w-auto">Save</span>

                            <span class="hidden md:flex">Translation</span>
                        </BaseButton>
                    </div>
                </div>
            </div>

            <tabs>
                <tab
                    prefix='<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 3.33334C8.81665 3.33334 5.83331 6.31668 5.83331 10C5.83331 13.6833 8.81665 16.6667 12.5 16.6667C16.1833 16.6667 19.1666 13.6833 19.1666 10C19.1666 6.31668 16.1833 3.33334 12.5 3.33334ZM12.5 15C9.74165 15 7.49998 12.7583 7.49998 10C7.49998 7.24168 9.74165 5.00001 12.5 5.00001C15.2583 5.00001 17.5 7.24168 17.5 10C17.5 12.7583 15.2583 15 12.5 15ZM5.83331 5.29168C3.89165 5.97501 2.49998 7.82501 2.49998 10C2.49998 12.175 3.89165 14.025 5.83331 14.7083V16.45C2.95831 15.7083 0.833313 13.1083 0.833313 10C0.833313 6.89168 2.95831 4.29168 5.83331 3.55001V5.29168Z"></path></svg>'
                    name="Similar">
                    <SimilarPhrases :similar-phrases="similarPhrases" />
                </tab>

                <tab prefix='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.25 2.52.77-1.28-3.52-2.09V8z"/></svg>' name="Versions">
                    <div class="flex h-48 w-full items-center justify-center px-4 py-6">
                        <span class="text-sm text-gray-500">Coming soon...</span>
                    </div>
                </tab>
            </tabs>
        </div>
    </LayoutDashboard>
</template>
