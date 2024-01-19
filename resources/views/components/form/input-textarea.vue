<script setup lang="ts">
defineProps<{
    error?: string
    rows?: string | number
}>()

const value = defineModel<string>()
</script>

<template>
    <textarea v-model="value" :rows="rows ?? 5" class="w-full rounded-md border-gray-300 shadow-sm placeholder:text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500" :class="{ 'border-red-300 text-red-900 placeholder:text-red-300 focus:border-red-500 focus:ring-red-500': error }"></textarea>
</template>
