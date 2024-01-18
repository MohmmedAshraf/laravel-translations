<script setup lang="ts">
import { Phrase, Translation } from "../../../scripts/types"

defineProps<{
    phrase: Phrase
    translation: Translation
}>()
</script>
<template>
    <div class="w-full hover:bg-gray-100">
        <div class="flex h-14 w-full divide-x">
            <div class="hidden w-20 items-center justify-center px-4 md:flex" :class="{ 'bg-green-50': phrase.state, 'hover:bg-green-100': phrase.state }">
                <IconCheck v-if="phrase.state" class="size-5 text-green-600" />

                <IconLanguage v-else class="size-5 text-gray-500" />
            </div>

            <Link :href="route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })" class="grid w-full grid-cols-2 divide-x md:grid-cols-3">
                <div class="flex w-full items-center justify-start px-4">
                    <div class="truncate rounded-md border bg-white px-1.5 py-0.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600">
                        {{ phrase.key }}
                    </div>
                </div>

                <div class="hidden w-full items-center justify-start px-4 md:flex">
                    <div class="truncate whitespace-nowrap text-sm font-medium text-gray-400">
                        {{ phrase.source?.value }}
                    </div>
                </div>

                <div class="flex w-full items-center justify-start px-4">
                    <div class="w-full truncate whitespace-nowrap text-sm font-medium text-gray-600">
                        {{ phrase.value }}
                    </div>
                </div>
            </Link>

            <div class="grid w-[67px] grid-cols-1 divide-x">
                <Link v-tooltip="'Edit'" :href="route('ltu.phrases.edit', { translation: translation.id, phrase: phrase.uuid })" class="group flex items-center justify-center px-3 hover:bg-blue-50">
                    <IconPencil class="size-5 text-gray-400 group-hover:text-blue-600" />
                </Link>
            </div>
        </div>
    </div>
</template>
