<script setup lang="ts">
defineProps<{
    message?: string
}>()
</script>

<template>
    <div v-show="message" class="flex items-center gap-1">
        <div class="flex size-4 items-center justify-center rounded-full border border-red-600">
            <IconClose class="size-3 text-red-600" />
        </div>

        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
