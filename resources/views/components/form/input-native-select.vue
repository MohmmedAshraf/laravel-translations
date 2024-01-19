<script setup lang="ts">
const props = defineProps<{
    items: any[]
    size?: "xs" | "sm" | "md" | "lg"
    error?: string
    placeholder?: string
}>()

const value = defineModel<any>()

const { sizeClass } = useInputSize(props.size ?? "lg")
</script>

<template>
    <select v-model="value" class="w-full rounded-md border border-gray-300 px-3 text-left shadow-sm focus:border-blue-500 focus:ring-blue-500" :class="[sizeClass, { 'border-red-300 text-red-900 placeholder:text-red-300 focus:border-red-500 focus:ring-red-500': error }]">
        <option value="" selected>{{ placeholder ?? "Select an option" }}</option>

        <option v-for="(item, index) in items" :key="index" :value="item.value">{{ item.label }}</option>
    </select>
</template>
