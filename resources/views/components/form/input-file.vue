<script setup lang="ts">
const value = defineModel<File | null>()

const update = (event: Event) => {
    value.value = (event.target as HTMLInputElement).files!.item(0)!
}
</script>

<template>
    <input class="mt-1 block text-sm file:mr-2 file:cursor-pointer file:border-2 file:border-gray-800 file:bg-white file:px-4 file:py-2 file:text-sm file:font-medium file:text-black file:transition hover:file:border-gray-600 hover:file:text-gray-600" type="file" @input="update" />
</template>
