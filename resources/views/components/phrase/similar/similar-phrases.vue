<script setup lang="ts">
import { FaceFrownIcon } from "@heroicons/vue/24/outline"
import { SourcePhrase } from "../../../../scripts/types"

defineProps<{
    similarPhrases: SourcePhrase
}>()
</script>

<template>
    <div v-if="similarPhrases.length > 0" class="flex flex-col divide-y">
        <SimilarPhrasesItem v-for="phrase in similarPhrases" :key="phrase.uuid" :phrase="phrase" />
    </div>

    <div v-else class="relative flex size-full min-h-[250px]">
        <div class="absolute left-0 top-0 flex min-h-full w-full flex-col items-center justify-center backdrop-blur-sm">
            <FaceFrownIcon class="size-12 text-gray-200" />

            <span class="mt-4 text-gray-500">No similar phrases found...</span>
        </div>
    </div>
</template>
