<script setup lang="ts">
import { computed } from "vue"
import { ExclamationCircleIcon } from "@heroicons/vue/20/solid"
import { TranslationFile } from "../../../../scripts/types"

const props = defineProps<{
    files: Record<string, TranslationFile>
}>()

const { close } = useModal()

const form = useForm({
    key: "",
    file: "",
    content: "",
})

const translationFiles = computed(() => {
    return props.files.map((fileType: TranslationFile) => {
        return {
            value: fileType.id,
            label: fileType.nameWithExtension,
        }
    })
})

const submit = () => {
    form.post(route("ltu.source_translation.store_source_key"), {
        preserveScroll: true,
        onSuccess: () => {
            close()
        },
    })
}
</script>

<template>
    <Head title="Add New Key" />

    <Dialog size="lg">
        <div class="flex items-start justify-between gap-4 border-b px-6 py-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-full border">
                <IconKey class="size-6 text-gray-400" />
            </div>

            <div class="w-full">
                <h3 class="text-base font-semibold leading-6 text-gray-600">Add New Key</h3>

                <p class="mt-1 text-sm text-gray-500">Add a new key to your source language.</p>
            </div>

            <div class="flex w-8 cursor-pointer items-center justify-center text-gray-400 hover:text-gray-600" @click="close">
                <IconClose class="size-5" />
            </div>
        </div>

        <div class="space-y-6 p-6">
            <div class="w-full space-y-1">
                <InputLabel for="file" value="Select file" />

                <InputNativeSelect id="file" v-model="form.file" :error="form.errors.file" :items="translationFiles" size="md" placeholder="Select translation file" />

                <InputError :message="form.errors.file" />
            </div>

            <div class="space-y-1">
                <InputLabel for="key" value="Source key" />

                <InputText id="key" v-model="form.key" :error="form.errors.key" placeholder="Enter key" />

                <InputError :message="form.errors.key" />

                <div class="flex w-full items-center gap-1">
                    <ExclamationCircleIcon class="size-4 text-gray-400" />

                    <span class="text-sm text-gray-400">You can use dot notation (.) to create nested keys.</span>
                </div>
            </div>

            <div class="space-y-1">
                <InputLabel for="content" value="Source content" />

                <InputTextarea id="content" v-model="form.content" :error="form.errors.content" placeholder="Enter translation content for this key." />

                <InputError :message="form.errors.content" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 border-t px-6 py-4 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" @click="close"> Close </BaseButton>

            <BaseButton variant="primary" type="button" size="lg" :disabled="form.processing" :is-loading="form.processing" @click="submit"> Create </BaseButton>
        </div>
    </Dialog>
</template>
