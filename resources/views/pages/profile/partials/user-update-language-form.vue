<script setup lang="ts">
import vSelect from "vue-select"
import { ref, Ref } from "vue"
import { Locale } from "../../../../scripts/types"
import { trans } from 'laravel-vue-i18n'

const props = defineProps<{
    locales: Locale
    localeSelected: Locale
}>()
const selected: Ref<Locale[]> = ref([])
const form = useForm({
    language: props.localeSelected || ref('')
})

const updateLanguage = () => {
    form.patch(route("ltu.profile.language.update"), {
        preserveScroll: true,
        onSuccess: () => {
            window.location.reload()
        },
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ trans('Update Language') }}</h2>

            <p class="mt-1 text-sm text-gray-600">{{ trans('Update Laravel Translations UI Language') }}
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="updateLanguage">
            <div class="space-y-1">
                <InputLabel for="language" :value="trans('Select Language')" />
                <input type="text" class="absolute m-0 h-0 p-0 opacity-0" autofocus />

                <div class="mt-0 w-full">
                    <vSelect v-model="form.language" label="language" class="rounded-md bg-white" :options="locales">
                        <template #search="{ attributes, events }">
                            <input class="vs__search" :required="!selected" v-bind="attributes"
                                :placeholder="!form.language ? trans('Search language...') : ''" v-on="events" />
                        </template>

                        <template #option="{ name, code }">
                            <div class="group flex w-full items-center justify-between">
                                <div class="flex flex-1 items-center gap-2">
                                    <flag width="w-6" :country-code="code" />

                                    <h3 class="font-medium">{{ trans(name) }}</h3>
                                </div>

                                <small
                                    class="shrink-0 rounded-md border px-1 py-0.5 text-xs text-gray-400 group-hover:text-white">{{
                                        code }}</small>
                            </div>
                        </template>

                        <template #selected-option="{ name, code }">
                            <flag width="w-5" :country-code="code" />

                            <h3 class="text-sm font-medium">{{ trans(name) }}</h3>
                        </template>
                    </vSelect>

                    <InputError :message="form.errors.language" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <BaseButton type="submit" size="md" variant="primary" :disabled="form.language === props.selected" :is-loading="form.processing"> {{ trans('Save') }}
                </BaseButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ trans('Saved.') }}</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
