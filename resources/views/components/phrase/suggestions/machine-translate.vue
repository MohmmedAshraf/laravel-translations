<script setup lang="ts">
import { FaceFrownIcon } from "@heroicons/vue/24/outline"
import { MachineTranslations } from "../../../../scripts/types"

defineProps<{
    language?: string
    suggestedTranslations: Record<string, MachineTranslations>
}>()

const emit = defineEmits<{
    (e: "useTranslation", value: string): void
}>()

const useTranslation = (value: string) => {
    emit("useTranslation", value)
}
</script>

<template>
    <div v-if="Object.keys(suggestedTranslations).length > 0" class="flex w-full flex-col divide-y">
        <MachineTranslateItem
            v-for="suggestedPhrase in suggestedTranslations"
            :key="suggestedPhrase.id"
            :phrase="suggestedPhrase"
            :language="language"
            @use-translation="useTranslation"
        />

        <div class="flex h-20 w-full items-center justify-center px-4 py-6">
            <span class="text-sm text-gray-500">More integrations coming in next releases...</span>
        </div>
    </div>

    <div v-else class="relative flex size-full min-h-[250px]">
        <div class="absolute left-0 top-0 flex min-h-full w-full flex-col items-center justify-center backdrop-blur-sm">
            <FaceFrownIcon class="size-12 text-gray-200" />

            <span class="mt-4 text-gray-500">No suggestions found...</span>
        </div>
    </div>
</template>
