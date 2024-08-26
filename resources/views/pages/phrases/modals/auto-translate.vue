<script setup lang="ts">

import { ArrowDownTrayIcon } from "@heroicons/vue/16/solid";
import {POSITION, useToast} from "vue-toastification";
import { Translation } from "@/scripts/types"

const props = defineProps<{
    isUntranslated: boolean
    translation: Translation
    source: Record<string, string>
    language: Record<string, string>
}>()

const { close } = useModal()

const loading = ref(false)

const submit = () => {
    loading.value = true

    router.post(route("ltu.phrases.translate", props.translation.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            loading.value = false

            close()
        },
    });
}
</script>

<template>
    <Head title="Auto Translate" />

    <Dialog size="lg">
        <div class="relative flex flex-col items-start justify-center gap-4 px-6 py-4">
            <div class="flex w-full items-start gap-4">
                <div class="flex size-16 shrink-0">
                    <IconLanguage class="size-10 text-blue-300" />
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-semibold leading-6 text-gray-600">
                        Auto Translate
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Translate all from {{ props.source.name }} ({{props.source.code}}) to {{ props.language.name }} ({{props.language.code}})
                    </p>
                </div>
            </div>

            <div class="w-full text-center">
                <Alert v-if="isUntranslated" variant="warning" class="text-left">
                    <strong>Warning:</strong>
                    Are you sure you want to translate everything from {{ props.source.name }} ({{props.source.code}}) to {{ props.language.name }} ({{props.language.code}})?
                    This may take a long time
                </Alert>

                <Alert v-else variant="success" class="text-left">
                    Nothing needs to be translated
                </Alert>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 border-t px-6 py-4 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" tabindex="1" @click="close"> Close </BaseButton>

            <BaseButton variant="primary" type="button" size="lg" :disabled="loading || !isUntranslated" :is-loading="loading" @click="submit">
                Yes, Auto Translate
            </BaseButton>
        </div>
    </Dialog>
</template>
