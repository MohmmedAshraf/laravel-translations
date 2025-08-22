<script setup lang="ts">
const user = useAuth()

const isTranslationsActive =
    route().current("ltu.translation*") ||
    route().current("ltu.source_translation*") ||
    route().current("ltu.phrases*")
</script>

<template>
    <div class="mx-auto py-2 max-w-7xl px-2 sm:px-4 lg:px-8">
        <div class="flex items-center justify-between lg:ml-4">
            <!-- Left button -->
            <Link :href="route('ltu.translation.index')" class="rounded-md px-3 py-2 text-sm font-medium" :class="[
                isTranslationsActive
                    ? 'bg-blue-500 text-white'
                    : 'text-white hover:bg-blue-700 hover:text-white'
            ]" :aria-current="isTranslationsActive ? 'page' : undefined">
            Translations
            </Link>

            <!-- Right button -->
            <Link :href="route('ltu.translation.publish')"
                class="btn btn-xs btn-success inline-flex items-center gap-1">
            <IconPublish class="size-4" />
            <span>Publish</span>
            </Link>
        </div>
    </div>
</template>
