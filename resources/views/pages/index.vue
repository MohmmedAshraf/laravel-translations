<script setup lang="ts">
import { ref } from "vue"
import { Translation, SourceTranslation } from "../../scripts/types"

const props = defineProps<{
    languages: {
        data: Translation[]
    }

    source_language: SourceTranslation
}>()

const selectedIds = ref<number[]>([]);

function toggleSelection() {
    if (selectedIds.value.length === props.languages.data.length) {
        selectedIds.value = []
    } else {
        selectedIds.value = props.languages.data.map((language: Translation) => language.id);
    }

    console.log(selectedIds.value)
}
</script>

<template>
    <Head title="Dashboard" />

    <LayoutDashboard>
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div v-if="source_language" class="mt-4 overflow-hidden rounded-md border bg-white shadow">
                <div class="relative z-10 flex w-full divide-x shadow">
                    <div class="flex w-14 shrink-0 items-center justify-center">
                        <div class="flex shrink-0 items-center">
                            <InputCheckbox :disabled="!languages.data.length" @click="toggleSelection" :checked="selectedIds.length" />
                        </div>
                    </div>

                    <div class="hidden flex-1 items-center px-4 text-sm text-gray-500 sm:flex">Language name</div>

                    <div class="md:max-w-72 lg:max-w-80 xl:max-w-96 hidden flex-1 items-center px-4 text-sm text-gray-500 md:flex">Translation progress</div>

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
                            <div
                                class="relative inline-flex h-14 w-14 select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out"
                                :class="{
                                    'cursor-not-allowed': !selectedIds.length,
                                    'cursor-pointer': selectedIds.length,
                                    'hover:bg-red-50 hover:text-red-600': selectedIds.length,
                                    'bg-gray-50': !selectedIds.length,
                                }"
                            >
                                <IconTrash class="h-5 w-5" />
                            </div>
                        </div>
                    </div>
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

                            <Link :href="route('ltu.translation.source_language')" class="flex w-full divide-x">
                                <div class="flex flex-1 justify-between gap-x-4 truncate px-4">
                                    <div class="flex w-full items-center truncate font-medium">
                                        <Flag :country-code="source_language.language.code" class="mr-2" />

                                        <span class="mr-2 max-w-full truncate text-base text-gray-700">
                                            {{ source_language.language.name }}
                                        </span>

                                        <div class="inline-block">
                                            <span class="flex h-5 items-center rounded-md border px-1.5 text-xs font-normal leading-none text-gray-600">
                                                {{ source_language.language.code }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:max-w-72 lg:max-w-80 xl:max-w-96 hidden flex-1 flex-wrap content-center items-center gap-1 px-4 md:flex">
                                    <span class="mr-1 shrink-0 text-sm leading-tight text-gray-600">{{ source_language.phrases_count }} source keys</span>
                                </div>
                            </Link>
                        </div>

                        <div class="group hidden w-full border-r sm:flex sm:w-14">
                            <div class="relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-700 outline-none transition-colors duration-150 ease-out focus:border-blue-50 group-hover:bg-blue-50 group-hover:text-blue-500">
                                <div class="visible flex h-full w-full items-center justify-center leading-none">
                                    <span class="mx-auto whitespace-nowrap sm:hidden">Manage Keys</span>

                                    <IconCog class="hidden h-5 w-5 fill-current text-gray-400 group-hover:text-blue-500 sm:flex" />
                                </div>
                            </div>
                        </div>

                        <div class="flex h-full">
                            <div class="flex w-full max-w-full">
                                <div class="relative inline-flex h-14 w-14 cursor-not-allowed select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out">
                                    <IconTrash class="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="languages.data.length">
                        <div v-for="language in languages.data" :key="language.language.code" class="flex h-14 flex-row gap-y-2 no-underline transition-colors duration-100 hover:bg-gray-50">
                            <div class="flex flex-1 divide-x border-r no-underline">
                                <div class="flex max-w-full">
                                    <div class="mr-0 flex w-14 items-center justify-center">
                                        <div class="flex shrink-0 items-center">
                                            <InputCheckbox v-model="selectedIds" :value="language.id" />
                                        </div>
                                    </div>
                                </div>

                                <Link :href="route('ltu.phrases.index', language.id)" class="flex w-full divide-x">
                                    <div class="flex flex-1 justify-between gap-x-4 truncate px-4">
                                        <div class="flex w-full items-center truncate font-medium">
                                            <Flag :country-code="language.language.code" class="mr-2" />

                                            <span class="mr-2 max-w-full truncate text-base text-gray-700">
                                                {{ language.language.name }}
                                            </span>

                                            <div class="inline-block">
                                                <span class="flex h-5 items-center rounded-md border px-1.5 text-xs font-normal leading-none text-gray-600">
                                                    {{ language.language.code }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:max-w-72 lg:max-w-80 xl:max-w-96 hidden flex-1 flex-wrap content-center items-center gap-1 px-4 md:flex">
                                        <div v-tooltip="`${language.progress}%` + ' strings translated'" class="w-full py-2">
                                            <div class="translation-progress w-full overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-2 bg-green-600" :style="{ width: `${language.progress}%` }"></div>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>

                            <div class="hidden w-full border-r sm:flex sm:w-14">
                                <Link :href="route('ltu.phrases.index', language.id)" class="relative inline-flex h-14 w-full cursor-pointer select-none items-center justify-center px-4 text-sm font-medium tracking-wide text-gray-400 outline-none transition-colors duration-150 ease-out hover:bg-blue-50 hover:text-blue-500 focus:border-blue-50">
                                    <div class="visible flex h-full w-full items-center justify-center leading-none">
                                        <span class="mx-auto whitespace-nowrap sm:hidden">Manage Keys</span>

                                        <IconLanguage class="hidden h-5 w-5 sm:flex" />
                                    </div>
                                </Link>
                            </div>

                            <div class="flex h-full">
                                <div class="flex w-full max-w-full">
                                    <Link as="button" method="DELETE" :href="route('ltu.translation.destroy', language.id)" class="relative inline-flex h-14 w-14 cursor-pointer select-none items-center justify-center p-4 text-sm font-medium uppercase tracking-wide text-gray-400 no-underline outline-none transition-colors duration-150 ease-out hover:bg-red-50 hover:text-red-600">
                                        <IconTrash class="h-5 w-5" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </template>

                    <Link v-if="languages.data.length" :href="route('ltu.translation.create')" class="flex cursor-pointer items-center justify-between rounded-b border-t p-4 text-gray-500 transition-colors duration-100 hover:bg-blue-50 hover:text-blue-600">
                        <div class="text-sm font-medium uppercase tracking-wide"> Add new language </div>

                        <IconPlus class="h-6 w-6" />
                    </Link>

                    <div v-else class="flex w-full flex-col items-center rounded bg-gray-50 px-4 py-32">
                        <IconEmptyTranslations class="mb-4 w-20 text-gray-400" />

                        <div class="text-center text-sm tracking-tight text-gray-400 sm:text-base">
                            There are no languages to translate your project to. <br />

                            Add the first one and start translating. <br />
                        </div>

                        <Link :href="route('ltu.translation.create')" class="btn btn-primary btn-md mt-4">
                            <div class="visible flex items-center leading-none">
                                <IconPlus class="mr-1 h-5 w-5 fill-white" />

                                <span class="whitespace-nowrap">Add languages</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <div v-else class="mt-4 flex flex-col items-center justify-center overflow-hidden rounded-md border bg-white py-36 shadow">
                <IconEmptyTranslations class="w-20 text-gray-400" />

                <div class="mt-6 flex flex-col items-center justify-center text-center text-sm tracking-tight text-gray-400 sm:text-base">
                    <span>Source language has not been imported yet.</span>

                    <span>To import the source language, click the button below.</span>
                </div>

                <Link :href="route('ltu.translation.create')" class="btn btn-primary btn-md mt-4">
                    <div class="visible flex items-center leading-none">
                        <IconPlus class="mr-1 h-5 w-5 fill-white" />

                        <span class="whitespace-nowrap">
                            Import source language
                        </span>
                    </div>
                </Link>
            </div>
        </div>
    </LayoutDashboard>
</template>
