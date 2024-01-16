<template>
    <div class="flex min-h-screen flex-col items-center justify-center bg-white px-4">
        <Link :href="route('ltu.translation.index')" class="flex h-10 max-w-max">
            <Logo class="h-10 w-auto fill-current text-gray-500" />
        </Link>

        <div class="flex w-full max-w-xs flex-col gap-y-2">
            <div class="flex flex-col items-center">
                <h1 class="mt-8 text-xl font-bold text-gray-700">
                    <slot name="title">Welcome back!</slot>
                </h1>

                <div v-if="$slots.subtitle" class="w-full text-center">
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        <slot name="subtitle" />
                    </p>
                </div>

                <div class="mt-6 w-full">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
