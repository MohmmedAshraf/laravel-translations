<script lang="ts" setup>
import { CheckCircleIcon, ExclamationCircleIcon, InformationCircleIcon, XCircleIcon } from "@heroicons/vue/20/solid"

const props = withDefaults(
    defineProps<{
        variant?: "info" | "success" | "warning" | "error"
    }>(),
    {
        variant: "info",
    },
)

const variantClass = computed(() => {
    return {
        info: "bg-blue-50 border border-blue-500 text-blue-700",
        success: "bg-green-50 border border-green-500 text-green-700",
        warning: "bg-yellow-50 border border-yellow-500 text-yellow-700",
        error: "bg-red-50 border border-red-500 text-red-700",
    }[props.variant]
})

const iconClasses = computed(() => {
    return {
        info: "text-blue-400",
        success: "text-green-400",
        warning: "text-yellow-400",
        error: "text-red-400",
    }[props.variant]
})

const icon = computed(() => {
    return {
        info: InformationCircleIcon,
        success: CheckCircleIcon,
        warning: ExclamationCircleIcon,
        error: XCircleIcon,
    }[props.variant]
})
</script>

<template>
    <div class="rounded-md px-2 py-2.5" :class="[variantClass]">
        <div class="flex items-start gap-2">
            <div class="mt-0.5 shrink-0">
                <component :is="icon" class="size-4" :class="iconClasses" aria-hidden="true" />
            </div>

            <div class="flex-1 md:flex md:justify-between">
                <p class="text-sm">
                    <slot />
                </p>
            </div>
        </div>
    </div>
</template>
