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
    <Head title="Add Languages" />

    <Dialog size="lg">
        <div class="flex gap-4 px-6 py-4 border-b items-start justify-between">
            <div class="flex flex-shrink-0 w-12 h-12 justify-center items-center rounded-full border">
                <IconPlus class="h-6 w-6 text-gray-400" />
            </div>

            <div class="w-full">
                <h3 class="text-base font-semibold leading-6 text-gray-600">Add Languages</h3>

                <p class="mt-1 text-sm text-gray-500">
                    Select the languages you want to add to your project.
                </p>
            </div>

            <div class="flex w-8 items-center justify-center text-gray-400 hover:text-gray-600 cursor-pointer" @click="close">
                <IconClose class="h-5 w-5" />
            </div>
        </div>

        <input type="text" class="m-0 h-0 p-0 opacity-0 absolute" autofocus>

        <div class="mt-0 w-full p-6">
            <vSelect
                v-model="form.languages"
                label="name"
                class="rounded-md bg-white"
                :options="languages"
                :reduce="(language: Language) => language.id"
                :selectable="(language: Language) => !form.languages.includes(language.id)"
                multiple
            >
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!selected"
                        v-bind="attributes"
                        v-on="events"
                        :placeholder="!form.languages.length ? 'Search languages...' : ''"
                    />
                </template>

                <template #option="{ name, code }">
                    <flag width="w-6" :country-code="code" />

                    <h3 class="font-medium">{{ name }}</h3>
                </template>

                <template #selected-option="{ name, code }">
                    <flag width="w-5" :country-code="code" />

                    <h3 class="text-sm font-medium">{{ name }}</h3>
                </template>
            </vSelect>
        </div>

        <div class="border-t gap-6 px-6 py-4 grid grid-cols-1 md:grid-cols-2">
            <BaseButton variant="secondary" type="button" size="lg" @click="close">
                Close
            </BaseButton>

            <BaseButton @click="submit" variant="primary" type="button" size="lg" :disabled="!form.languages.length || form.processing" :is-loading="form.processing">
                Add Languages
            </BaseButton>
        </div>
    </Dialog>
</template>
