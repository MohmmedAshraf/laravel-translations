<script lang="ts" setup>
import { useModal } from "momentum-modal"
import { TransitionRoot, TransitionChild, Dialog, DialogPanel } from "@headlessui/vue"

const props = withDefaults(
    defineProps<{
        size?: "sm" | "md" | "lg" | "xl" | "2xl"
        closeable?: boolean
    }>(),
    {
        size: "lg",
        closeable: true,
    },
)

const { show, close, redirect } = useModal()

const closeDialog = () => {
    if (props.closeable) {
        close()
    }
}

const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[props.size]
})
</script>

<template>
    <TransitionRoot appear as="template" :show="show">
        <Dialog as="div" class="relative z-50" @close="closeDialog">
            <TransitionChild as="template" leave-to="opacity-0" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" @after-leave="redirect">
                <div class="fixed inset-0 bg-gray-500/25 backdrop-blur transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel :class="[maxWidthClass]" class="w-full rounded-2xl bg-white text-left align-middle shadow-xl transition-all">
                            <slot />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
