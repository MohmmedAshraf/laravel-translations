<script setup lang="ts">
import { ref } from "vue";
import { XCircleIcon } from "@heroicons/vue/20/solid";
import { SparklesIcon } from "@heroicons/vue/24/outline";
import { Phrase, Translation, SourceTranslation } from "../../../scripts/types"

const props = defineProps<{
    phrase: Phrase
    translation: Translation
    source: SourceTranslation
}>()

const form = useForm({
    updatePhrase: ref(props.phrase.value) || '',
});

const submit = () => {
    form.post(route('ltu.phrases.update', { translation: props.translation.id, phrase: props.phrase.uuid }), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const copyOriginal = () => {
    form.updatePhrase = props.phrase.source.value;
};

function copyParameters(text: string): void {
    const textarea = document.getElementById('textArea') as HTMLTextAreaElement;
    const scrollPos = textarea.scrollTop;
    let strPos = 0;
    const br = textarea.selectionStart != undefined ? "ff" : (document.selection ? "ie" : false);

    function createRange(): Range | TextRange {
        return br === "ie"
            ? document.selection.createRange()
            : (document.createRange() as Range);
    }

    function setCursorPosition(position: number): void {
        if (br === "ie") {
            const range = createRange();
            range.moveStart('character', -textarea.value.length);
            range.moveStart('character', position);
            range.moveEnd('character', 0);
            range.select();
        } else if (br === "ff") {
            textarea.selectionStart = position;
            textarea.selectionEnd = position;
            textarea.focus();
        }
    }

    if (br === "ie") {
        textarea.focus();
        const range = createRange();
        range.moveStart('character', -textarea.value.length);
        strPos = range.text.length;
    } else if (br === "ff") {
        strPos = textarea.selectionStart as number;
    }

    const front = textarea.value.substring(0, strPos);
    const back = textarea.value.substring(strPos, textarea.value.length);
    textarea.value = front + text + back;
    strPos = strPos + text.length;

    setCursorPosition(strPos);

    textarea.scrollTop = scrollPos;
}
</script>
<template>
    <Head title="Translate" />

    <LayoutDashboard>
        <div class="w-full bg-white shadow">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 lg:px-8">
                <div class="flex w-full items-center">
                    <div class="flex w-full items-center gap-3 py-4">
                        <Link href="#" class="flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100">
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

                <Link v-tooltip="'Go back'" :href="route('ltu.phrases.index', translation.id)" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200">
                    <IconArrowRight class="h-6 w-6 text-gray-400" />
                </Link>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex w-full flex-col lg:flex-row">
                <div class="relative w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100">
                    <div class="flex items-center justify-between border-b">
                        <div class="flex gap-2 px-4 py-2.5">
                            <div class="h-5 shrink-0">
                                <flag :country-code="source.language.code" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <div class="text-sm font-semibold text-gray-800" v-text="source.language.name"></div>

                                <div class="rounded-md border px-1.5 py-0.5 text-xs text-gray-500" v-text="source.language.code"></div>
                            </div>
                        </div>

                        <div class="flex items-center divide-x border-l">
                            <button v-tooltip="'Copy'" type="button" class="group h-full p-3 hover:bg-blue-50" @click="copyOriginal">
                                <IconClipboard class="h-5 w-5 text-gray-400 group-hover:text-blue-600" />
                            </button>

                            <Link :href="route('ltu.translation.source_language.edit', phrase.source.uuid)" v-tooltip="'Edit Source Key'" type="button" class="group h-full p-3 hover:bg-blue-50">
                                <IconPencil class="h-5 w-5 text-gray-400 group-hover:text-blue-600" />
                            </Link>

                            <button v-tooltip="'Delete'" type="button" class="group h-full p-3 hover:bg-red-50">
                                <IconTrash class="h-5 w-5 text-gray-400 group-hover:text-red-600" />
                            </button>
                        </div>
                    </div>


                    <div dir="auto" class="flex min-h-[204px] w-full px-4 py-2.5">
                        <div class="flex h-full w-full flex-wrap gap-x-1 gap-y-0.5">
                            <template v-if="phrase.source?.value_html.length">
                                <div
                                    v-for="word in phrase.source?.value_html"
                                    :key="word.value"
                                    class="flex h-full text-gray-600"
                                    :class="{
                                    'cursor-pointer rounded bg-blue-50 px-1.5 py-0 !text-blue-600 hover:bg-blue-100': word.parameter
                                }"
                                    @click="word.parameter && copyParameters(word.value)"
                                >
                                    {{ word.value }}
                                </div>
                            </template>

                            <div v-else class="flex h-full text-gray-600">
                                {{ phrase.source?.value }}
                            </div>
                        </div>
                    </div>

                    <div class="flex select-none items-center px-4 py-2.5 gap-1 text-gray-400 border-t border-blue-200">
                        <IconDocument class="h-4 w-4" />

                        <div class="text-xs">validation.php</div>
                    </div>
                </div>

                <div class="flex h-16 w-full items-center justify-center lg:h-auto lg:w-32">
                    <IconArrowRight class="h-12 w-12 rotate-90 text-blue-200 lg:rotate-0" />
                </div>

                <div class="w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100 focus-within:ring-blue-400">
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

                        <div v-if="form.errors.updatePhrase" class="rounded-md border border-red-400 bg-red-50 px-3 py-1">
                            <div class="flex items-center gap-1">
                                <div class="shrink-0">
                                    <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                                </div>

                                <div class="text-sm text-red-700">
                                    {{ form.errors.updatePhrase }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        <input-textarea id="textArea" v-model="form.updatePhrase" dir="auto" rows="7" autofocus class="h-full w-full resize-none rounded-none border-none px-4 py-2.5 shadow-none ring-transparent focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0" />
                    </div>

                    <div class="grid grid-cols-2 border-t border-blue-200">
                        <Link :href="route('ltu.phrases.index', translation.id)" class="flex items-center justify-center bg-blue-50 py-4 text-sm font-medium uppercase text-blue-600 hover:bg-blue-100">
                            Cancel
                        </Link>

                        <BaseButton :is-loading="form.processing" variant="primary" class="items-center rounded-none !py-4 !text-sm !font-medium uppercase" @click="submit">
                            <span class="flex w-auto">Save</span>

                            <span class="hidden md:flex">Translation</span>
                        </BaseButton>
                    </div>
                </div>
            </div>

            <div class="mt-12 overflow-hidden rounded-md bg-white shadow">
                <div class="flex items-center divide-x border-b">
                    <div class="flex gap-2 px-4 py-3.5">
                        <IconStar class="h-5 w-5 text-gray-500" />

                        <span class="text-sm font-medium text-gray-600">Suggestions</span>
                    </div>

                    <div class="flex gap-2 px-4 py-3.5">
                        <IconLanguage class="h-5 w-5 text-gray-500" />

                        <span class="text-sm font-medium text-gray-600">Languages</span>
                    </div>

                    <div class="flex gap-2 px-4 py-3.5">
                        <IconVersions class="h-5 w-5 text-gray-500" />

                        <span class="text-sm font-medium text-gray-600">Versions</span>
                    </div>

                    <div class="flex gap-2 px-4 py-3.5">
                        <IconSimilar class="h-5 w-5 text-gray-500" />

                        <span class="text-sm font-medium text-gray-600">Similar</span>
                    </div>
                </div>

                <div class="flex min-h-[350px] flex-col items-center justify-center space-y-4 p-4">
                    <SparklesIcon class="h-10 w-10 text-gray-400" />

                    <span class="text-sm font-medium text-gray-400">No suggestions available</span>
                </div>
            </div>
        </div>
    </LayoutDashboard>
</template>
