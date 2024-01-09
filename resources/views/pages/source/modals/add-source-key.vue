<script setup lang="ts">
import vSelect from "vue-select";
import { computed, ref, Ref } from "vue";
import { Language } from "../../../../scripts/types"

const { close } = useModal()

const selected: Ref<Language[]> = ref([]);

defineProps<{
    languages: Language
}>();

const form = useForm({
    languages: [],
});

const submit = () => {
    form.post(route('ltu.translation.store'), {
        preserveScroll: true,
        onSuccess: () => {
            close();
        },
    });
};
</script>

<template>
    <Head title="Add New Key" />

    <Dialog size="lg">
        <div class="flex gap-4 px-6 py-4 border-b items-start justify-between">
            <div class="flex flex-shrink-0 w-12 h-12 justify-center items-center rounded-full border">
                <IconKey class="h-6 w-6 text-gray-400" />
            </div>

            <div class="w-full">
                <h3 class="text-base font-semibold leading-6 text-gray-600">Add New Key</h3>

                <p class="mt-1 text-sm text-gray-500">
                    Add a new key to your source language.
                </p>
            </div>

            <div class="flex w-8 items-center justify-center text-gray-400 hover:text-gray-600 cursor-pointer" @click="close">
                <IconClose class="h-5 w-5" />
            </div>
        </div>

        <div>
            ssss
        </div>

        <div class="border-t gap-6 px-6 py-4 grid grid-cols-1 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" @click="close">
                Close
            </BaseButton>

            <BaseButton @click="submit" variant="primary" type="button" size="lg" :disabled="!form.languages.length || form.processing" :is-loading="form.processing">
                Save & Add Next
            </BaseButton>
        </div>
    </Dialog>
</template>
