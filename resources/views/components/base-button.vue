<script lang="ts" setup>
import useButtonSize from "@/scripts/composables/use-button-size"
import useButtonVariant from "@/scripts/composables/use-button-variant"

const props = withDefaults(
    defineProps<{
        size?: "xs" | "sm" | "md" | "lg"
        type?: "button" | "submit" | "reset"
        variant?: "primary" | "secondary" | "success" | "danger" | "warning" | "dark"
        fullWidth?: boolean
        isLoading?: boolean
    }>(),
    {
        size: "lg",
        type: "button",
        variant: "primary",
        fullWidth: false,
        isLoading: false,
    },
)

const { sizeClass } = useButtonSize(props.size)
const { variantClass } = useButtonVariant(props.variant)
</script>

<template>
    <button :type="type" class="btn" :class="[sizeClass, variantClass, { 'w-full': fullWidth }]" :disabled="isLoading">
        <span class="flex items-center gap-2" :class="{ 'opacity-0': isLoading }">
            <slot />
        </span>

        <span v-if="isLoading" class="absolute left-0 top-0 flex size-full items-center justify-center">
            <icon-loading class="size-6 animate-spin text-white" />
        </span>
    </button>
</template>
