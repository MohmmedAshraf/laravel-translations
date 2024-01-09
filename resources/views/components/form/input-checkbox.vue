<script setup lang="ts">
defineProps<{ value?: any }>()

const checked = defineModel<any[] | boolean>("modelValue")
</script>

<template>
    <input v-model="checked" :value="value" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
</template>
