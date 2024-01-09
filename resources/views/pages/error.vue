<script setup lang="ts">
defineProps<{
    code?: string;
    text?: string;
    title?: string;
}>()
</script>

<template>
    <Head :title="title" />

    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div class="text-center">
            <p class="text-base font-semibold text-indigo-600">
                {{ code }}
            </p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                {{ title }}
            </h1>
            <p class="mt-6 text-base leading-7 text-gray-600">
                {{ text }}
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <Link :href="route('ltu.translation.index')" class="btn btn-md btn-primary">
                    <IconArrowLeft class="h-5 w-5" />
                    <span>Go back home</span>
                </Link>
            </div>
        </div>
    </main>
</template>
