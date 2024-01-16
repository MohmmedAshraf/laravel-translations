<script setup lang="ts">
import { ref, watch } from "vue"
import { Translation } from "../../../scripts/types"

const props = defineProps<{
    phrases: Object
    translation: Translation
}>()

const form = useForm({
    search: ref(""),
    status: ref(""),
})

watch(form, () => {
    form.get(route("ltu.source_translation", { translation: props.translation.id }), {
        replace: true,
        onSuccess: () => {
            form.reset()
        },
    })
})
</script>
<template>
    <Head title="Base Language" />

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

                        <div class="rounded-md border bg-white px-1.5 py-0.5 text-sm text-gray-500" v-text="translation.language.code"></div>
                    </div>
                </div>

                <Link v-tooltip="'Go back'" :href="route('ltu.translation.index')" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200">
                    <IconArrowRight class="h-6 w-6 text-gray-400" />
                </Link>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="w-full divide-y overflow-hidden rounded-md bg-white shadow">
                <div class="grid w-full grid-cols-8 justify-between px-4 py-3">
                    <div class="col-span-2">
                        <InputText placeholder="Search" v-model="form.search" size="md" />
                    </div>

                    <div class="col-span-4"></div>

                    <div class="col-span-2">
                        <InputNativeSelect
                            size="md"
                            id="status"
                            placeholder="Filter by status"
                            v-model="form.status"
                            :error="form.errors.status"
                            :items="[
                                { value: 'translated', label: 'Translated' },
                                { value: 'untranslated', label: 'Untranslated' },
                            ]" />
                    </div>
                </div>

                <div class="w-full shadow-md">
                    <div class="flex h-14 w-full divide-x">
                        <div class="flex w-12 items-center justify-center p-4">
                            <InputCheckbox />
                        </div>

                        <div class="hidden w-20 items-center justify-center px-4 md:flex">
                            <span class="text-sm font-medium text-gray-400">State</span>
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
                                <IconPlus class="h-5 w-5 text-gray-400 group-hover:text-blue-600" />
                            </Link>

                            <div class="flex items-center justify-center">
                                <IconEllipsisVertical class="h-5 w-5 text-gray-400" />
                            </div>
                        </div>
                    </div>
                </div>

                <div v-for="phrase in phrases.data" :key="phrase.id" class="w-full hover:bg-gray-100">
                    <div class="flex h-14 w-full divide-x">
                        <div class="flex w-12 items-center justify-center p-4">
                            <InputCheckbox />
                        </div>

                        <div class="hidden w-20 items-center justify-center px-4 md:flex" :class="{ 'bg-green-50': phrase.state, 'hover:bg-green-100': phrase.state }">
                            <IconCheck v-if="phrase.state" class="h-5 w-5 text-green-600" />

                            <IconLanguage v-else class="h-5 w-5 text-gray-500" />
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
                                <IconPencil class="h-5 w-5 text-gray-400 group-hover:text-blue-600" />
                            </Link>

                            <Link v-tooltip="'Delete'" href="route('ltu.confirmation')" type="button" class="group flex items-center justify-center px-3 hover:bg-red-50">
                                <IconTrash class="h-5 w-5 text-gray-400 group-hover:text-red-600" />
                            </Link>
                        </div>
                    </div>
                </div>

                <Pagination :links="phrases.links" :meta="phrases.meta" />
            </div>
        </div>
    </LayoutDashboard>
</template>
