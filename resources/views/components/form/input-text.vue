<script setup lang="ts">
import useInputSize from "@/scripts/composables/use-input-size"

const props = withDefaults(
    defineProps<{
        type?: "text" | "email" | "password" | "number" | "search" | "tel" | "url"
        size?: "xs" | "sm" | "md" | "lg"

        error?: string
    }>(),
    {
        type: "text",

        size: "lg",
        error: "",
    },
)

const input = ref<HTMLInputElement | null>(null)

const value = defineModel<string | number | null>()

const { sizeClass } = useInputSize(props.size)

onMounted(() => {
    nextTick(() => {
        if (input.value?.hasAttribute("autofocus")) {
            input.value?.focus()
        }
    })
})

defineExpose({ focus: () => input.value?.focus() })
</script>

<template>
    <input
        ref="input"
        v-model="value"
        :type="type"
        class="w-full rounded-md border-gray-300 placeholder:text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500"
        :class="[sizeClass, { 'border-red-300 text-red-900 placeholder:text-red-300 focus:border-red-500 focus:ring-red-500': error }]"
    />
</template>
