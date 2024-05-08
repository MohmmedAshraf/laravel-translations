<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'

const props = defineProps<{ modelValue: string | null }>()

const emit = defineEmits<{
    (e: "update:modelValue", value: string | null): void
    (e: "reset"): void
}>()

const keyword = computed({
    get: () => props.modelValue,
    set: (newValue: string | null) => emit("update:modelValue", newValue),
})
</script>

<template>
    <div class="flex items-center">
        <div class="flex w-full rounded bg-white shadow">

            <input
                v-model="keyword"
                class="focus:shadow-outline relative w-full rounded-r px-6 py-3"
                autocomplete="off"
                type="text"
                name="search"
                :placeholder="trans('Search')"
            />
        </div>

        <button
            class="ml-3 min-w-max text-sm text-gray-500 hover:text-gray-700 focus:text-indigo-500"
            type="button"
            @click="$emit('reset')"
        >
            {{ trans('Reset') }}
        </button>
    </div>
</template>
