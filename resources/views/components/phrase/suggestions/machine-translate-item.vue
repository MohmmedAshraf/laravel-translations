<script setup lang="ts">
import { onMounted, ref } from "vue"
import { useSpeechSynthesis } from "@vueuse/core"
import { MachineTranslations } from "../../../../scripts/types"
import useLanguageCodeConversion from "../../../../scripts/composables/use-language-code-conversion"

const props = defineProps<{
    language?: string
    phrase: MachineTranslations
}>()

const emit = defineEmits<{
    (e: "useTranslation", value: string): void
}>()

const useTranslation = (value: string) => {
    emit("useTranslation", value)
}

const { convertLanguageCode } = useLanguageCodeConversion();

const rate = ref(1)
const pitch = ref(1)
const text = props.phrase.value
const langCode = convertLanguageCode(props.language)
const lang = langCode || "en-US"
const voice = ref<SpeechSynthesisVoice>(undefined as unknown as SpeechSynthesisVoice)

const speech = useSpeechSynthesis(text, { lang, voice, pitch, rate })

let synth: SpeechSynthesis

const voices = ref<SpeechSynthesisVoice[]>([])

onMounted(() => {
    if (speech.isSupported.value) {
        setTimeout(() => {
            synth = window.speechSynthesis
            voices.value = synth.getVoices()
            voice.value = voices.value[0]
        })
    }
})

</script>

<template>
    <div class="flex flex-wrap divide-x">
        <div class="px-4 py-3 md:w-64">
            <div class="mt-2 flex items-center">
                <IconGoogle class="mr-2 size-4 text-gray-500" />

                <div class="text-sm font-medium text-gray-700" v-text="phrase.engine"></div>
            </div>
        </div>

        <div class="flex flex-1 items-center px-4 py-3">
            <span class="text-gray-700">{{ phrase.value }}</span>
        </div>

        <div class="flex divide-x">
            <button
                v-tooltip="'Use this'"
                class="flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100 hover:bg-blue-100 hover:text-blue-600"
                @click="useTranslation(phrase.value)"
            >
                <IconLanguage class="size-6" />
            </button>

            <button
                v-tooltip="langCode ? 'Speak' : 'Language not supported'"
                class="flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100"
                :class="{ 'cursor-not-allowed opacity-50': !langCode, ' hover:bg-blue-100 hover:text-blue-600': langCode }"
                :disabled="!langCode"
                @click="langCode && speech.speak()"
            >
                <IconSpeak class="size-6" />
            </button>
        </div>
    </div>
</template>
