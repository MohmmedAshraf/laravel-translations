<script setup lang="ts">
import { withDefaults } from "vue"

withDefaults(
    defineProps<{
        code: string
        title: string
        text: string
    }>(),
    {
        code: "404",
        title: "Page not found",
        text: "Sorry, we couldn't find the page you're looking for.",
    },
)
</script>

<template>
    <Head :title="title" />

    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div class="text-center">
            <span class="text-base font-semibold text-indigo-600">
                {{ code }}
            </span>

            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                {{ title }}
            </h1>

            <span class="mt-6 text-base leading-7 text-gray-600">
                {{ text }}
            </span>

            <div class="mt-10 flex items-center justify-center gap-x-6">
                <Link :href="route('ltu.translation.index')" class="btn btn-md btn-primary">
                    <IconArrowLeft class="size-5" />

                    <span>Go back home</span>
                </Link>
            </div>
        </div>
    </main>
</template>
