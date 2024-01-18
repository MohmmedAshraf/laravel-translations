<script setup lang="ts">
import { Phrase } from "../../../../scripts/types"

defineProps<{
    phrase: Phrase
}>()
</script>
<template>
    <div class="flex w-full flex-row divide-x">
        <div class="mb-2 flex w-48 items-start px-4 py-3 md:w-80 xl:w-96">
            <div class="flex w-full max-w-max overflow-hidden truncate rounded-md border">
                <span class="flex cursor-pointer bg-white px-1 py-px text-sm text-gray-700 hover:bg-blue-50" v-text="phrase.key"></span>
            </div>
        </div>

        <div class="flex flex-1 items-center px-3 py-2 text-base">
            <div v-if="phrase?.value_html.length && phrase?.value_html[0]?.value" class="flex flex-wrap items-center gap-1">
                <PhraseWithParameters :phrase="phrase.value_html" />
            </div>

            <div v-else class="flex text-gray-600">
                {{ phrase?.value ?? "Not translated yet..." }}
            </div>
        </div>

        <a :href="route('ltu.source_translation.edit', phrase.uuid)" target="_blank" class="transition-color relative flex w-14 cursor-pointer items-center justify-center text-gray-400 duration-100 hover:bg-blue-100 hover:text-blue-600">
            <IconPencil class="inline-block size-5" />
        </a>
    </div>
</template>
