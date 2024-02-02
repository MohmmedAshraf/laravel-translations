<script setup lang="ts">

import { ArrowDownTrayIcon } from "@heroicons/vue/16/solid";
import {POSITION, useToast} from "vue-toastification";

defineProps<{
    canPublish: boolean
    isProductionEnv: boolean
}>()

const toast = useToast()

const { close } = useModal()

const loading = ref(false)
const downloadLoading = ref(false)

const submit = () => {
    loading.value = true

    router.post(route("ltu.translation.export"), {}, {
        preserveScroll: true,
        onSuccess: () => {
            loading.value = false

            close()
        },
    });
}

const download = () => {
    downloadLoading.value = true

    fetch(route("ltu.translation.download"))
        .then(async (res) => {
            let blob = await res.blob();
            downloadLoading.value = false;
            const link = document.createElement('a');
            const file = window.URL.createObjectURL(blob);
            link.download = 'lang.zip';
            link.href = file;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            downloadLoading.value = false;

            close()
        })
        .catch((error) => {
            toast.error("Something went wrong, please try again.", {
                icon: true,
                position: POSITION.BOTTOM_CENTER,
            })
        });
};
</script>

<template>
    <Head title="Add Languages" />

    <Dialog size="lg">
        <div v-if="canPublish" class="relative flex flex-col items-start justify-center gap-4 px-6 py-4">
            <div class="flex w-full items-start gap-4">
                <div class="flex size-16 shrink-0">
                    <IconPublish class="size-16 text-blue-300" />
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-semibold leading-6 text-gray-600">
                        Publish Translations
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Be cautious when publishing, as this action will update your project with all translations and changes made in the database, overwriting project files.
                    </p>
                </div>
            </div>

            <div class="w-full text-center">
                <Alert v-if="!isProductionEnv" variant="warning" class="text-left">
                    <strong>Warning:</strong> This is a Production Environment. Publishing translations will causing issues with version control and other developers, Please use the download button below instead.
                </Alert>

                <BaseButton v-if="!isProductionEnv" :disabled="loading || downloadLoading" :is-loading="downloadLoading" variant="dark" size="lg" class="mt-4" full-width target="_blank" @click="download">
                    <ArrowDownTrayIcon class="size-4" />

                    <span>Download</span>
                </BaseButton>
            </div>
        </div>

        <div v-else class="relative flex flex-col items-start justify-center gap-4 px-6 py-4">
            <div class="mx-auto flex size-12 shrink-0 items-center justify-center">
                <span role="img" class="text-4xl">🤔</span>
            </div>

            <div class="w-full text-center">
                <h3 class="text-xl font-semibold leading-6 text-gray-600">
                    What are you trying to do?
                </h3>

                <p class="mt-4 text-sm text-gray-500">
                    Add languages to your project, then come back to publish translations when you're ready.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 border-t px-6 py-4" :class="{ 'md:grid-cols-2': canPublish }">
            <BaseButton variant="secondary" type="button" size="lg" tabindex="1" @click="close"> Close </BaseButton>

            <BaseButton v-if="canPublish" variant="primary" type="button" size="lg" :disabled="loading || downloadLoading" :is-loading="loading" @click="submit">
                Publish
            </BaseButton>
        </div>
    </Dialog>
</template>
