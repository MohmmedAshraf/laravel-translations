<script lang="ts" setup>
defineProps<{
    links: Object
    meta: Object
}>()
</script>

<template>
    <div class="group flex w-full items-center justify-between gap-6 p-4">
        <Link v-if="links.prev" :href="links.prev" preserve-scroll>
            <IconArrowLeft class="h-6 w-6 text-gray-400 group-hover:text-blue-600" />
        </Link>

        <button v-else type="button" class="cursor-not-allowed" disabled>
            <IconArrowLeft class="h-6 w-6 text-gray-400" />
        </button>

        <span class="text-sm font-medium text-gray-500 group-hover:text-blue-600"> {{ meta.current_page }} of {{ meta.last_page }} </span>

        <Link v-if="links.next" :href="links.next" preserve-scroll>
            <IconArrowRight class="h-6 w-6 text-gray-400 group-hover:text-blue-600" />
        </Link>

        <button v-else type="button" class="cursor-not-allowed" disabled>
            <IconArrowRight class="h-6 w-6 text-gray-400" />
        </button>
    </div>
</template>
