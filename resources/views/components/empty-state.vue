<script setup lang="ts">
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <div class="flex w-full flex-col items-center justify-center overflow-hidden px-4">
        <slot name="icon" />

        <div class="mt-6 flex flex-col items-center justify-center text-center text-sm tracking-tight text-gray-400 sm:text-base">
            <span v-text="title" />

            <span v-text="description" />
        </div>

        <slot />
    </div>
</template>
